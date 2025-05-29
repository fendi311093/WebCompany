<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\RelationManagers;
use App\Models\HeaderButton;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class NavigasiResource extends Resource
{
    protected static ?string $model = HeaderButton::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Navigation';
    protected static ?string $navigationLabel = 'Navigation';
    protected static ?string $pluralModelLabel = 'List of Navigations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('type_button')
                        ->label('Type Navigation')
                        ->placeholder('Please select type navigation')
                        ->rules(fn($record) => HeaderButton::getValidationRules($record)['type_button'])
                        ->validationMessages(HeaderButton::getValidationMessages()['type_button'])
                        ->options([
                            1 => 'Top Header',
                            2 => 'Sub Header'
                        ])
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state == 1) {
                                $set('position_sub_header', 0); // Sub header direset
                            } elseif ($state == 2) {
                                $set('position_header', 0); // Header direset
                            }
                        }),
                    Select::make('position_header')
                        ->label('Position Header')
                        ->required()
                        ->default(0)
                        ->disableOptionWhen(fn($value) => in_array($value, HeaderButton::getUsedPositionHeader()))
                        ->options([
                            0 => 'None',
                            1 => 'Navigation 1',
                            2 => 'Navigation 2',
                            3 => 'Navigation 3',
                            4 => 'Navigation 4',
                            5 => 'Navigation 5',
                            6 => 'Navigation 6',
                            7 => 'Navigation 7',
                            8 => 'Navigation 8',
                            9 => 'Navigation 9',
                            10 => 'Navigation 10',
                        ])
                        ->visible(fn(callable $get) => $get('type_button') == 1),
                    Select::make('position_sub_header')
                        ->label('Position Sub Header')
                        ->required()
                        ->default(0)
                        ->disableOptionWhen(fn($value) => in_array($value, HeaderButton::getUsedPositionSubHeader()))
                        ->options([
                            0 => 'None',
                            1 => 'Sub Navigation 1',
                            2 => 'Sub Navigation 2',
                            3 => 'Sub Navigation 3',
                            4 => 'Sub Navigation 4',
                            5 => 'Sub Navigation 5',
                            6 => 'Sub Navigation 6',
                            7 => 'Sub Navigation 7',
                            8 => 'Sub Navigation 8',
                            9 => 'Sub Navigation 9',
                            10 => 'Sub Navigation 10',
                        ])
                        ->visible(fn(callable $get) => $get('type_button') == 2),
                ])->columns(2),
                Section::make()
                    ->description('Turn On adding URL to link to other website addresses')
                    ->icon('heroicon-m-information-circle')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('page_id')
                                ->label('Page')
                                ->placeholder('Please select page')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['page_id'])
                                ->validationMessages(HeaderButton::getValidationMessages()['page_id'])
                                ->options(HeaderButton::getPageOptions())
                                ->searchable()
                                ->preload()
                                ->disableOptionWhen(fn($value) => in_array($value, HeaderButton::getUsedPageIds())),
                            TextInput::make('name_button')
                                ->label('Name Button')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['name_button'])
                                ->validationMessages(HeaderButton::getValidationMessages()['name_button'])
                                ->dehydrateStateUsing(fn($state) => strtoupper($state))
                                ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                                ->live(onBlur: true),
                            TextInput::make('slug')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                        Toggle::make('is_active_button')
                            ->label('Active Button')
                            ->default(true)
                            ->onColor('primary')
                            ->offColor('danger')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle')
                            ->columns(1),
                        Grid::make(2)->schema([
                            Toggle::make('is_active_url')
                                ->label('Adding URL')
                                ->default(false)
                                ->onColor('primary')
                                ->offColor('danger')
                                ->onIcon('heroicon-o-check-badge')
                                ->offIcon('heroicon-o-x-circle')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (!$state) {
                                        $set('url', null);
                                    }
                                }),
                            TextInput::make('url')
                                ->label('URL')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['url'])
                                ->validationMessages(HeaderButton::getValidationMessages()['url'])
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->visible(fn(callable $get) => $get('is_active_url') == true)
                                ->required(fn(callable $get) => $get('is_active_url') == true),
                        ])
                            ->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('type_button')
                    ->label('Type Nav')
                    ->badge()
                    ->color(fn($record) => match ($record->type_button) {
                        1 => 'primary',
                        2 => 'success'
                    })
                    ->formatStateUsing(fn($record) => match ($record->type_button) {
                        1 => 'Top Header',
                        2 => 'Sub Header'
                    }),
                TextColumn::make('name_button')
                    ->label('Name Nav'),
                TextColumn::make('position_header')
                    ->label('Header')
                    ->formatStateUsing(
                        fn($record) => $record->type_button == 1
                            ? match ($record->position_header) {
                                0 => 'None',
                                1 => 'Position 1',
                                2 => 'Position 2',
                                3 => 'Position 3',
                                4 => 'Position 4',
                                5 => 'Position 5',
                                6 => 'Position 6',
                                7 => 'Position 7',
                                8 => 'Position 8',
                                9 => 'Position 9',
                                10 => 'Position 10',
                                default => '-',
                            }
                            : '-'
                    ),

                TextColumn::make('position_sub_header')
                    ->label('Sub Header')
                    ->formatStateUsing(
                        fn($record) => $record->type_button == 2
                            ? match ($record->position_sub_header) {
                                0 => 'None',
                                1 => 'Position 1',
                                2 => 'Position 2',
                                3 => 'Position 3',
                                4 => 'Position 4',
                                5 => 'Position 5',
                                6 => 'Position 6',
                                7 => 'Position 7',
                                8 => 'Position 8',
                                9 => 'Position 9',
                                10 => 'Position 10',
                                default => '-',
                            }
                            : '-'
                    ),
                ToggleColumn::make('is_active_button')
                    ->label('Active Nav')
                    ->onColor('primary')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-badge')
                    ->offIcon('heroicon-o-x-circle'),
                IconColumn::make('is_active_url')
                    ->label('Active URL')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Navigations Button Found')
            ->emptyStateDescription('You can create a new navigation button by clicking the "Create" button Above.')
            ->emptyStateIcon('heroicon-o-list-bullet');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNavigasis::route('/'),
        ];
    }
}
