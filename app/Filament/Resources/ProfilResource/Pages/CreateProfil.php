<?php

namespace App\Filament\Resources\ProfilResource\Pages;

use App\Filament\Resources\ProfilResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Profil;
use Filament\Notifications\Notification;

class CreateProfil extends CreateRecord
{
    protected static string $resource = ProfilResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Profil::count() > 0) {
            Notification::make()
                ->title('Hanya boleh ada satu data profil di database.')
                ->danger()
                ->send();

            $this->halt(); // Batalkan proses create
        }
        return $data;
    }
}
