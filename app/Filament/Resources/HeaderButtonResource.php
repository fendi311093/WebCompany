<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeaderButtonResource\Pages;
use App\Filament\Resources\HeaderButtonResource\RelationManagers;
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
use Illuminate\Support\Str;

class HeaderButtonResource extends Resource
{
    protected static ?string $model = HeaderButton::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            1 => 'Nav Button 1',
                            2 => 'Nav Button 2',
                            3 => 'Nav Button 3',
                            4 => 'Nav Button 4',
                            5 => 'Nav Button 5',
                            6 => 'Nav Button 6',
                            7 => 'Nav Button 7',
                            8 => 'Nav Button 8',
                            9 => 'Nav Button 9',
                            10 => 'Nav Button 10',
                        ]),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalAutofocus(false),
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
        ];
    }
}
