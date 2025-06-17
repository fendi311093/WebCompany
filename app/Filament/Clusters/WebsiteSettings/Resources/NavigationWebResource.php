<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigationWebResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigationWebResource\RelationManagers;
use App\Models\NavigationWeb;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class NavigationWebResource extends Resource
{
    protected static ?string $model = NavigationWeb::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = WebsiteSettings::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Navigation';
    protected static ?string $navigationLabel = 'Navigation';
    protected static ?string $pluralModelLabel = 'List of Navigation Webs';

    protected static ?array $cachedPageOptions = null;

    public static function form(Form $form): Form
    {

        // Cache options at class level to prevent duplicate queries
        if (static::$cachedPageOptions === null) {
            static::$cachedPageOptions = NavigationWeb::getPagesOptions();
            // dd(static::$cachedPageOptions);
        }

        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        Toggle::make('is_active_page')
                            ->label('Active Page')
                            ->default(true)
                            ->onColor('primary')
                            ->offColor('danger')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle')
                            ->reactive(),
                        Select::make('page_id')
                            ->label('Select Page')
                            ->placeholder('Select a page')
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get) => $get('is_active_page') == true)
                            ->options(static::$cachedPageOptions)
                            ->rules(fn($record): array => NavigationWeb::getValidationRules($record)['page_id'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['page_id']),
                        Toggle::make('is_active_link')
                            ->label('Link Active')
                            ->default(false)
                            ->onColor('primary')
                            ->offColor('danger')
                            ->onIcon('heroicon-o-check-badge')
                            ->offIcon('heroicon-o-x-circle')
                            ->reactive(),
                        TextInput::make('link')
                            ->label('URL Link')
                            ->placeholder('https://example.com')
                            ->suffixIcon('heroicon-m-globe-alt')
                            ->visible(fn(callable $get) => $get('is_active_link') == true)
                            ->rules(fn($record): array => NavigationWeb::getValidationRules($record)['link'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['link']),
                    ])
                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('type')
                            ->label('Type Button')
                            ->placeholder('Select a type button')
                            ->options([
                                'header' => 'Header',
                                'dropdown' => 'Dropdown',
                            ])
                            ->rules(fn($record) => NavigationWeb::getValidationRules($record)['type'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['type']),
                        TextInput::make('title')
                            ->label('Title')
                            ->dehydrateStateUsing(fn($state): string => strtoupper($state))
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                            ->rules(fn($record): array => NavigationWeb::getValidationRules($record)['title'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['title']),
                        TextInput::make('slug')
                            ->placeholder('Auto Generated From Title')
                            ->disabled()
                            ->dehydrated()
                            ->rules(fn($record): array => NavigationWeb::getValidationRules($record)['slug'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['slug']),
                        Select::make('position')
                            ->placeholder('Select a position')
                            ->searchable()
                            ->preload()
                            ->options([
                                '1' => 'Position 1',
                                '2' => 'Position 2',
                                '3' => 'Position 3',
                                '4' => 'Position 4',
                                '5' => 'Position 5',
                                '6' => 'Position 6',
                                '7' => 'Position 7',
                                '8' => 'Position 8',
                                '9' => 'Position 9',
                                '10' => 'Position 10',
                            ])
                            ->rules(fn($record): array => NavigationWeb::getValidationRules($record)['position'])
                            ->validationMessages(NavigationWeb::getValidationMessages()['position'])
                            ->disableOptionWhen(function ($value, $record, $get) {
                                $type = $get('type');
                                $ignoreId = $record?->id;
                                $usedPositions = NavigationWeb::getUsedPositionsWithCache($type, $ignoreId);
                                return in_array($value, $usedPositions);
                            })
                    ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        // Cache options at class level to prevent duplicate queries
        if (static::$cachedPageOptions === null) {
            static::$cachedPageOptions = NavigationWeb::getPagesOptions();
        }

        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('type')
                    ->label('Type Button')
                    ->formatStateUsing(fn($state): string => strtoupper($state))
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'header' => 'primary',
                        'dropdown' => 'warning'
                    }),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('position')
                    ->formatStateUsing(fn($state) => 'POSITION - ' . $state),
                TextColumn::make('page_id')
                    ->label('Page')
                    ->default('-')
                    ->formatStateUsing(fn($state) => static::$cachedPageOptions[$state] ?? '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active_page')
                    ->label('Page Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('link')
                    ->icon('heroicon-m-globe-alt')
                    ->default(fn($record) => $record->is_active_link ? $record->link : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active_link')
                    ->label('Link Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->label('')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->size(ActionSize::Small)
                    ->Button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigationWebs::route('/'),
            'create' => Pages\CreateNavigationWeb::route('/create'),
            'edit' => Pages\EditNavigationWeb::route('/{record}/edit'),
        ];
    }
}
