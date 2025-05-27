<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\ContentResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
