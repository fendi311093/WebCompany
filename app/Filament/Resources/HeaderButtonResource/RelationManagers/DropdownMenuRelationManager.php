<?php

namespace App\Filament\Resources\HeaderButtonResource\RelationManagers;

use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DropdownMenuRelationManager extends RelationManager
{
    protected static string $relationship = 'dropdownMenus';
    protected static ?string $title = 'Dropdown Menu';
    protected static ?string $modelLabel = 'Dropdown';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(),
                Select::make('headerButton_id')
                    ->relationship('headerButton', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('page_id')
                    ->label('Select Page')
                    ->options(fn() => Page::with('source')->get()->mapWithKeys(fn($page) => [
                        $page->id => match ($page->source_type) {
                            'App\Models\Profil' => "Profil - {$page->source->name_company}",
                            'App\Models\Customer' => "Customer - {$page->source->name_customer}",
                            'App\Models\Content' => "Content - {$page->source->title}",
                            default => "Unknown - {$page->id}"
                        }
                    ]))
                    ->searchable()
                    ->preload()
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateHeading('No dropdown menu found')
            ->emptyStateDescription('Please create a dropdown menu to get started')
            ->EmptyStateIcon('heroicon-o-arrow-down-on-square-stack');
    }
}
