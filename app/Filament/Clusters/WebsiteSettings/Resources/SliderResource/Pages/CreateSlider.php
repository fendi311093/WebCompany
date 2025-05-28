<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\SliderResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\SliderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
