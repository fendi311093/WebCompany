<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\SliderResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\SliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSliders extends ListRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
