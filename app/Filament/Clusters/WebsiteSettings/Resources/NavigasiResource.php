<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources;

use App\Filament\Clusters\WebsiteSettings;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\Pages;
use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\RelationManagers;
use App\Models\HeaderButton;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
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
    protected static ?string $pluralModelLabel = 'List of Navigation Buttons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Type Navigation')->schema([
                    Select::make('type_button')
                        ->label('Type Button')
                        ->options([
                            'Header_nav'    => 'Header Navigation',
                            'Sub_nav'       => 'Sub Navigation',
                        ]),
                    Select::make('content_id')
                        ->label('Content'),
                    Select::make('position')
                        ->label('Header Position')
                        ->options([
                            1 => 'Nav 1',
                            2 => 'Nav 2',
                            3 => 'Nav 3',
                            4 => 'Nav 4',
                            5 => 'Nav 5',
                            6 => 'Nav 6',
                            7 => 'Nav 7',
                            8 => 'Nav 8',
                            9 => 'Nav 9',
                            10 => 'Nav 10',
                        ]),
                    Select::make('sub_position')
                        ->label('Parent Header')
                ]),
                Fieldset::make('Header View')->schema([
                    TextInput::make('title')
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                        ->live(onBlur: true),
                    TextInput::make('slug')
                        ->disabled()
                        ->dehydrated(),

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
