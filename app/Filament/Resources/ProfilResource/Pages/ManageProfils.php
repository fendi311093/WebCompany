<?php

namespace App\Filament\Resources\ProfilResource\Pages;

use App\Filament\Resources\ProfilResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProfils extends ManageRecords
{
    protected static string $resource = ProfilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
