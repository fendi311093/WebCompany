<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\SliderResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\SliderResource;
use App\Models\Photo;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Vinkla\Hashids\Facades\Hashids;

class EditSlider extends EditRecord
{
    protected static string $resource = SliderResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Konversi ID asli ke hash ID untuk ditampilkan di form
        if (isset($data['photo_id']) && is_numeric($data['photo_id'])) {
            $data['photo_id'] = Hashids::encode($data['photo_id']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Konversi hash photo_id ke ID asli
        if (isset($data['photo_id']) && is_string($data['photo_id'])) {
            $photo = Photo::findByHashedId($data['photo_id']);
            if ($photo) {
                $data['photo_id'] = $photo->id;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
