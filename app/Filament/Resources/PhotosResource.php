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
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Model;

class PhotosResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Photos';
    protected static ?string $modelLabel = 'Photos';
    protected static ?string $pluralLabel = 'List Photos';
    protected static ?string $navigationGroup = 'Media';
    protected static ?int $navigationSort = 91;

    // Di Filament, method ini digunakan untuk menemukan record berdasarkan ID di URL
    public static function resolveRecordRouteBinding(int|string $key): ?Model
    {
        return static::getModel()::findByHashedId($key);
    }

    public static function form(Form $form): Form
    {
        // Closure untuk pengecekan halaman create
        $isCreatePhoto = fn($livewire) => $livewire instanceof Pages\CreatePhoto;

        return $form
            ->schema([
                Section::make()
                    ->description(fn($livewire) => $isCreatePhoto($livewire) ? 'You can upload multiple photo, max 10' : null)
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
                            ->maxSize(11000)
                            ->helperText('Max size photo 11MB.')
                            ->required()
                            ->previewable(fn($livewire) => !$isCreatePhoto($livewire))
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file) => Photo::generateSafeFileName($file))
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
