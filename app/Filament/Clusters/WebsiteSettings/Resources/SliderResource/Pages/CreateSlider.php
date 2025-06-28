<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\SliderResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\SliderResource;
use App\Models\Photo;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
