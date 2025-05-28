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
use Filament\Tables;
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
                        ->reactive(),
                    Select::make('position_header')
                        ->label('Position Header')
                        ->default(0)
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
                        ->default(0)
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
                        Grid::make(2)->schema([
                            Select::make('page_id')
                                ->label('Page')
                                ->placeholder('Please select page')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['page_id'])
                                ->validationMessages(HeaderButton::getValidationMessages()['page_id'])
                                ->options(HeaderButton::getPageOptions())
                                ->searchable()
                                ->preload(),
                            TextInput::make('name_button')
                                ->label('Name Button')
                                ->rules(fn($record) => HeaderButton::getValidationRules($record)['name_button'])
                                ->validationMessages(HeaderButton::getValidationMessages()['name_button'])
                                ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                            Toggle::make('is_active_button')
                                ->label('Active Button')
                                ->default(true)
                                ->onColor('primary')
                                ->offColor('danger')
                                ->onIcon('heroicon-o-check-badge')
                                ->offIcon('heroicon-o-x-circle')
                                ->inlineLabel(),
                        ]),
                        Grid::make()->schema([
                            Toggle::make('is_active_url')
                                ->label('Adding URL')
                                ->default(false)
                                ->onColor('primary')
                                ->offColor('danger')
                                ->onIcon('heroicon-o-check-badge')
                                ->offIcon('heroicon-o-x-circle')
                                ->reactive(),
                            TextInput::make('url')
                                ->label('URL')
                                ->default('https://example.com')
                                ->url()
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->visible(fn(callable $get) => $get('is_active_url') == true)
                        ])->inlineLabel()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
