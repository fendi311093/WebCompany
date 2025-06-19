<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotosResource\Pages;
use App\Filament\Resources\PhotosResource\RelationManagers;
use App\Models\Photo;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PhotosResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Photos';
    protected static ?string $modelLabel = 'Photos';
    protected static ?string $pluralLabel = 'List Photos';
    protected static ?string $navigationGroup = 'Media';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        // Closure untuk pengecekan halaman create
        $isCreatePhoto = fn($livewire) => $livewire instanceof Pages\CreatePhoto;

        return $form
            ->schema([
                Section::make()
                    ->description(fn($livewire) => $isCreatePhoto($livewire) ? 'You can select multiple photos at once' : null)
                    ->icon(fn($livewire) => $isCreatePhoto($livewire) ? 'heroicon-o-information-circle' : null)
                    ->iconColor(fn($livewire) => $isCreatePhoto($livewire) ? 'success' : null)
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Photo')
                            ->multiple(fn($livewire) => $isCreatePhoto($livewire))
                            ->maxFiles(fn($livewire) => $isCreatePhoto($livewire) ? 10 : 1)
                            ->image()
                            ->disk('public')
                            ->directory('Photos')
                            ->visibility('public')
                            // ->downloadable()
                            ->maxSize(11000)
                            ->helperText('Max size photo 11MB.')
                            ->required()
                            ->previewable(fn($livewire) => !$isCreatePhoto($livewire)),
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
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
            ->emptyStateHeading('No Photos Found')
            ->emptyStateDescription('You have not uploaded any photos yet. Click the button below to upload your first photo.')
            ->emptyStateIcon('heroicon-o-camera')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhotos::route('/'),
            // 'create' => Pages\UploadPhotos::route('/create'),
            'create' => Pages\CreatePhoto::route('/create'),
            'edit' => Pages\EditPhoto::route('/{record}/edit'),
        ];
    }
}
