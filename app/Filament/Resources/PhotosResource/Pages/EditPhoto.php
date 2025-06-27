<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPhoto extends EditRecord
{
    protected static string $resource = PhotosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return null;
    }

    protected function afterSave(): void
    {
        \Filament\Notifications\Notification::make()
            ->title('Photo updated successfully!')
            ->icon('heroicon-o-camera')
            ->color('success')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
