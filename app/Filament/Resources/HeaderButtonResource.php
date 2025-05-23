<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeaderButtonResource\Pages;
use App\Filament\Resources\HeaderButtonResource\RelationManagers;
use App\Filament\Resources\HeaderButtonResource\RelationManagers\DropdownMenuRelationManager;
use App\Models\HeaderButton;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Str;

class HeaderButtonResource extends Resource
{
    protected static ?string $model = HeaderButton::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Website Settings';
    protected static ?string $navigationLabel = 'Navigation';
    protected static ?string $modelLabel = 'Navigation';
    protected static ?string $pluralLabel = 'List Navigations';
    protected static ?int $navigationSort = 24;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Header Button')->schema([
                    TextInput::make('title')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->rules(fn($record) => HeaderButton::ValidationRules($record)['title'])
                        ->dehydrateStateUsing(fn(String $state): String => strtoupper($state)),
                    TextInput::make('slug')
                        ->rules(fn($record) => HeaderButton::ValidationRules($record)['slug'])
                        ->disabled()
                        ->dehydrated(),
                    Select::make('position')
                        ->rules(fn($record) => HeaderButton::ValidationRules($record)['position'])
                        ->options([
                            1 => 'NAV BUTTON 1',
                            2 => 'NAV BUTTON 2',
                            3 => 'NAV BUTTON 3',
                            4 => 'NAV BUTTON 4',
                            5 => 'NAV BUTTON 5',
                            6 => 'NAV BUTTON 6',
                            7 => 'NAV BUTTON 7',
                            8 => 'NAV BUTTON 8',
                            9 => 'NAV BUTTON 9',
                            10 => 'NAV BUTTON 10',
                        ])
                        ->disableOptionWhen(fn($value) => HeaderButton::getUsedPosition($value)),
                    Select::make('page_id')
                        ->label('Select Page')
                        ->options(function () {
                            return Page::with('source')->get()->mapWithKeys(function ($page) {
                                $label = match ($page->source_type) {
                                    'App\Models\Profil' => $page->source->name_company,
                                    'App\Models\Customer' => $page->source->name_customer,
                                    'App\Models\Content' => $page->source->title,
                                    default => 'Unknown'
                                };
                                return [$page->id => $label];
                            });
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Link Active')
                        ->default(true)
                        ->onIcon('heroicon-m-check-badge')
                        ->offIcon('heroicon-m-x-circle')
                        ->onColor('success')
                        ->offColor('danger')
                        ->inline(false),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('position')
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'NAV BUTTON 1',
                        2 => 'NAV BUTTON 2',
                        3 => 'NAV BUTTON 3',
                        4 => 'NAV BUTTON 4',
                        5 => 'NAV BUTTON 5',
                        6 => 'NAV BUTTON 6',
                        7 => 'NAV BUTTON 7',
                        8 => 'NAV BUTTON 8',
                        9 => 'NAV BUTTON 9',
                        10 => 'NAV BUTTON 10',
                        default => 'Unknown',
                    }),
                TextColumn::make('page_id')
                    ->label('Page Contents')
                    ->formatStateUsing(fn($state) => Page::find($state)->source->name_company),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger'),
            ])->defaultSort('updated_at', 'desc')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHeaderButtons::route('/'),
            'edit' => Pages\EditHeaderButtons::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            DropdownMenuRelationManager::class,
        ];
    }
}
