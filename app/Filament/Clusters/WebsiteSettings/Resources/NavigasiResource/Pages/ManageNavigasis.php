<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\NavigasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNavigasis extends ManageRecords
{
    protected static string $resource = NavigasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
