<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoUploaderResource\Pages;
use App\Models\Photo;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;

class PhotoUploaderResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Upload Multiple Photos';

    protected static ?string $slug = 'multi-photo-uploader';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photos')
                    ->label('Pilih Foto (dapat multi-pilih)')
                    ->helperText('Klik area kotak atau tombol Browse untuk memilih foto')
                    ->required()
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->disk('public')
                    ->directory('Photos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\UploadPhotos::route('/'),
        ];
    }
}
