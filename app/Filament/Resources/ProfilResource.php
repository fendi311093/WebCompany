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
use App\Rules\ValidationProfil;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProfilResource extends Resource
{
    protected static ?string $model = Profil::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Profil Company';
    protected static ?string $modelLabel = 'Profil';
    protected static ?string $pluralLabel = 'Profil Company';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name_company')
                        ->label('Name Company')
                        ->dehydrateStateUsing(fn($state) => strtoupper($state))
                        ->rules(fn($record) => Profil::getValidationRules($record)['name_company']),
                    TextInput::make('phone')
                        ->numeric()
                        ->rules(fn($record) => Profil::getValidationRules($record)['phone']),
                    FileUpload::make('logo')
                        ->required()
                        ->image()
                        ->maxSize(11000)
                        ->imageEditor()
                        ->directory('Logo_Profil')
                        ->getUploadedFileNameForStorageUsing(
                            function (TemporaryUploadedFile $file, $record, $get): string {
                                $profilName = $get('name_company') ?? ($record?->name_company ?? 'Logo');
                                $saveName = \Illuminate\Support\Str::slug($profilName);
                                $extension = $file->getClientOriginalExtension();
                                return "Logo-{$saveName}." . $extension;
                            }
                        ),
                    FileUpload::make('photo')
                        ->required()
                        ->image()
                        ->maxSize(11000)
                        ->imageEditor()
                        ->directory('Photo_Profil')
                        ->getUploadedFileNameForStorageUsing(
                            function (TemporaryUploadedFile $file, $record, $get): string {
                                $profilName = $get('name_company') ?? ($record?->name_company ?? 'Profil');
                                $saveName = \Illuminate\Support\Str::slug($profilName);
                                $extension = $file->getClientOriginalExtension();
                                return "Profil-{$saveName}." . $extension;
                            }
                        ),
                ])->columns(2)
                    ->inlineLabel(),
                Section::make()->schema([
                    MarkdownEditor::make('address')
                        ->disableToolbarButtons([
                            'link',
                            'attachFiles'
                        ])
                        ->rules(fn($record) => Profil::getValidationRules($record)['address']),
                    MarkdownEditor::make('description')
                        ->disableToolbarButtons([
                            'link',
                            'attachFiles'
                        ])
                        ->rules(fn($record) => Profil::getValidationRules($record)['description']),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('name_company')
                    ->label('Name Company'),
                TextColumn::make('address')
                    ->words(4),
                TextColumn::make('phone'),
                ImageColumn::make('logo'),
                ImageColumn::make('photo')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalAutofocus(false),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip('Actions')
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
            'create' => Pages\CreateProfil::route('/create'),
        ];
    }
}
