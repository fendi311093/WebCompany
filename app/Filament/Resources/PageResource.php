<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Customer;
use App\Models\Page;
use App\Models\Profil;
use App\Models\Content;
use App\Models\HeaderButton;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Page';
    protected static ?string $modelLabel = 'Page';
    protected static ?string $pluralLabel = 'List Pages';
    protected static ?string $navigationGroup = 'Website Settings';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('source_type')
                    ->label('Source Type')
                    ->options([
                        'App\Models\Profil' => 'Profil',
                        'App\Models\Customer' => 'Customer',
                        'App\Models\Content' => 'Content',
                    ])
                    ->required()
                    ->reactive(),
                Select::make('source_id')
                    ->label('Select Page')
                    ->preload()
                    ->searchable()
                    ->options(fn(callable $get) => Page::getSourceOptions($get('source_type')))
                    ->required()
                    ->visible(fn(callable $get) => filled($get('source_type'))),
                Toggle::make('is_active')
                    ->label('Page is Active')
                    ->default(true),
            ])->columns(1)
            ->inlineLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('source_type')
                    ->label('Source Type')
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'App\Models\Profil' => 'PROFIL',
                        'App\Models\Customer' => 'CUSTOMER',
                        'App\Models\Content' => 'CONTENT',
                        default => $state ?? '-',
                    }),
                TextColumn::make('source')
                    ->label('Source Data')
                    ->formatStateUsing(function ($record) {
                        if (!$record->source) return '-';

                        return match ($record->source_type) {
                            'App\Models\Profil' => $record->source->name_company,
                            'App\Models\Customer' => $record->source->name_customer,
                            'App\Models\Content' => $record->source->title,
                            default => '-',
                        };
                    }),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check-badge')
                    ->offIcon('heroicon-m-x-circle')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->modalAutofocus(false),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['source']));
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
            'index' => Pages\ManagePages::route('/'),
        ];
    }
}
