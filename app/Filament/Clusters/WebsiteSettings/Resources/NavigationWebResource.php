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
use Filament\Tables;
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
            static::$cachedPageOptions = NavigationWeb::getPages();
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
                            ->options(static::$cachedPageOptions),
                        Toggle::make('is_active_url')
                            ->label('URL Active')
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
                            ->visible(fn(callable $get) => $get('is_active_url') == true)
                            ->required(fn(callable $get) => $get('is_active_url') == true),
                    ])
                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('type')
                            ->label('Type Button')
                            ->options([
                                'header' => 'Header',
                                'dropdown' => 'Dropdown',
                            ]),
                        TextInput::make('title')
                            ->label('Title')
                            ->dehydrateStateUsing(fn($state): string => strtoupper($state))
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->placeholder('Auto Generated From Title')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('position')
                            ->placeholder('Select a position')
                            ->searchable()
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
                    ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
