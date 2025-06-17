<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\NavigationWebResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\NavigationWebResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNavigationWebs extends ListRecords
{
    protected static string $resource = NavigationWebResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
