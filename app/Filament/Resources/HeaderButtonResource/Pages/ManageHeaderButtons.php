<?php

namespace App\Filament\Resources\HeaderButtonResource\Pages;

use App\Filament\Resources\HeaderButtonResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageHeaderButtons extends ManageRecords
{
    protected static string $resource = HeaderButtonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
