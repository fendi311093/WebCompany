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
                    $photo = Photo::create($data);

                    // Resize photo jika ukuran lebih dari 1Mb
                    // pengecekan lagi karena tidak menggunakan event listener di model PHOTO
                    $fileLocation = storage_path('app/public/' . $photo->file_path);
                    if (file_exists($fileLocation) && filesize($fileLocation) > 1024 * 1024) {
                        dispatch(new ResizePhotoJob($photo->id, 'Photo', 'file_path'))->delay(now()->addMinutes(5));
                    }
                }
            }
            // Resize photo jika ukuran lebih dari 1Mb
            $record = $this->record;
            if ($record) {

                // pengecekan lagi karena tidak menggunakan event listener di model PHOTO
                $fileLocation = storage_path('app/public/' . $record->file_path);
                if (file_exists($fileLocation) && filesize($fileLocation) > 1024 * 1024) {
                    dispatch(new ResizePhotoJob($record->id, 'Photo', 'file_path'))->delay(now()->addMinutes(5));
                }
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
