<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Facility';
    protected static ?string $modelLabel = 'Facility';
    protected static ?string $pluralLabel = 'List Facility';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->minLength(6)
                    ->maxLength(150)
                    ->unique(ignoreRecord: true)
                    ->autocapitalize('characters'),
                RichEditor::make('description')
                    ->disableGrammarly()
                    ->disableToolbarButtons([
                        'link',
                        'attachFiles'
                    ]),
                FileUpload::make('photo')
                    ->required()
                    ->image()
                    ->imageEditor()
                    ->directory('Photo_Facility')
                    ->getUploadedFileNameForStorageUsing(
                        function (TemporaryUploadedFile $file, $record, $get): string {
                            $facilityName = $get('title') ?? ($record?->title ?? 'Facility');
                            $saveName = \Illuminate\Support\Str::slug($facilityName);
                            $extension = $file->getClientOriginalExtension();
                            return "facility-{$saveName}." . $extension;
                        }
                    ),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('title'),
                ImageColumn::make('photo')
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
            ->emptyStateDescription('Once you create a facility, it will appear here')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFacilities::route('/'),
        ];
    }
}
