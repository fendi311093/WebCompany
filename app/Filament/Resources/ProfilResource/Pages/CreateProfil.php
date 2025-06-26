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
                ->title('There can only be one profile data in the database.')
                ->danger()
                ->send();

            $this->halt(); // Batalkan proses create
        }
        return $data;
    }

    // Get the URL to redirect after creating the record
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
