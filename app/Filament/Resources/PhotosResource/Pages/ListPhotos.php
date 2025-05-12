<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;

class ListPhotos extends ListRecords
{
    protected static string $resource = PhotosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New photo'),

            Action::make('uploadMultiple')
                ->label('Upload Multiple Photos')
                ->icon('heroicon-o-camera')
                ->color('success')
                ->size(ActionSize::Large)
                ->url(route('filament.admin.resources.multi-photo-uploader.index'))
                ->openUrlInNewTab(false),
        ];
    }
}
