<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\PageResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePages extends ManageRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('xl'),
        ];
    }
}
