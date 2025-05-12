<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoUploaderResource\Pages;
use App\Models\Photo;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class PhotoUploaderResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = null;

    protected static ?string $navigationLabel = 'Upload Multiple Photos';

    protected static ?string $slug = 'multi-photo-uploader';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photos')
                    ->label('Pilih Foto (dapat multi-pilih)')
                    ->required()
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('Photos')
                    ->visibility('public')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\UploadPhotos::route('/'),
        ];
    }
}
