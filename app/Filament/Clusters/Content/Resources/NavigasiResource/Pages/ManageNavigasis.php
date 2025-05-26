<?php

namespace App\Filament\Clusters\Content\Resources\NavigasiResource\Pages;

use App\Filament\Clusters\Content\Resources\NavigasiResource;
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
