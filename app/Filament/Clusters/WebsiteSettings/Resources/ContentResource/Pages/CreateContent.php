<?php

namespace App\Filament\Clusters\WebsiteSettings\Resources\ContentResource\Pages;

use App\Filament\Clusters\WebsiteSettings\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;
}
