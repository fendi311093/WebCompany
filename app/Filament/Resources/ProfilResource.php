<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilResource\Pages;
use App\Filament\Resources\ProfilResource\RelationManagers;
use App\Models\Profil;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfilResource extends Resource
{
    protected static ?string $model = Profil::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Profil Company';
    protected static ?string $modelLabel = 'Profil';
    protected static ?string $pluralLabel = 'Profil Company';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name_company')
                        ->label('Name Company'),
                    TextInput::make('phone')
                        ->numeric(),
                ])->columns(2),
                Section::make()->schema([
                    MarkdownEditor::make('address')
                        ->disableToolbarButtons([
                            'link',
                            'attachFiles'
                        ]),
                    FileUpload::make('photo'),
                    MarkdownEditor::make('description')
                        ->disableToolbarButtons([
                            'link',
                            'attachFiles'
                        ]),
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
            ->emptyStateHeading('No posts yet')
            ->emptyStateDescription('Once you create a profil, it will appear here')
            ->emptyStateIcon('heroicon-o-building-office');;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProfils::route('/'),
        ];
    }
}
