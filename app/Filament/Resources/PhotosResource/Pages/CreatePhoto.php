<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Photo;
use App\Jobs\ResizePhotoJob;
use Filament\Notifications\Notification;

class CreatePhoto extends CreateRecord
{
    protected static string $resource = PhotosResource::class;

    protected $photosDataList = [];

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return null;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $photos = $data['file_path'] ?? [];
        unset($data['file_path']);
        $dataList = [];
        foreach ($photos as $filePath) {
            $dataList[] = array_merge($data, [
                'file_path' => $filePath,
            ]);
        }
        // Simpan data list ke property untuk digunakan di afterCreate
        $this->photosDataList = $dataList;
        // Return satu data saja agar Filament tidak error
        return $dataList[0] ?? $data;
    }

    protected function afterCreate(): void
    {
        try {
            $count = isset($this->photosDataList) ? count($this->photosDataList) : 1;
            // Insert sisanya jika ada lebih dari satu foto
            if (isset($this->photosDataList) && $count > 1) {
                array_shift($this->photosDataList);
                foreach ($this->photosDataList as $data) {
                    $photo = \App\Models\Photo::create($data);
                    dispatch(new \App\Jobs\ResizePhotoJob($photo->id, 'Photo', 'file_path'));
                }
            }
            // Resize untuk record utama
            $record = $this->record;
            if ($record) {
                dispatch(new \App\Jobs\ResizePhotoJob($record->id, 'Photo', 'file_path'));
            }
            // Notifikasi sukses
            \Filament\Notifications\Notification::make()
                ->title('Done. Uploading photos ...!')
                ->body("{$count} photo uploaded successfully")
                ->icon('heroicon-o-camera')
                ->color('success')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('Gagal upload photo: ' . $e->getMessage())
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->danger()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
