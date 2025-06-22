<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    // Get the URL to redirect after creating the record
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
