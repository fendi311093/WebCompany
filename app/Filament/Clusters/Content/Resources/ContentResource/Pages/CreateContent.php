<?php

namespace App\Filament\Clusters\Content\Resources\ContentResource\Pages;

use App\Filament\Clusters\Content\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;
}
