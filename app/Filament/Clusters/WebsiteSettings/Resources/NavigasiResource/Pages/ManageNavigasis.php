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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['type_button'] == 1) {
            $data['position_sub_header'] = 0;
        }
        if ($data['type_button'] == 2) {
            $data['position_header'] = 0;
        }

        return $data;
    }
}
