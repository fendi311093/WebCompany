<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\SubNavigationResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\SubNavigationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubNavigations extends ManageRecords
{
    protected static string $resource = SubNavigationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
