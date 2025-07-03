<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Photo;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListPhotos extends ListRecords
{
    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.list-photos';
    protected static ?string $title = '';

    public $perPage = 10;

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function getPhotos()
    {
        return Photo::latest()->paginate($this->perPage);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New photo'),
        ];
    }

    public function deletePhoto($photoId): void
    {
        $photo = Photo::find($photoId);

        if ($photo) {
            $photo->delete();

            Notification::make()
                ->title('Photo deleted')
                ->body('The photo has been deleted successfully.')
                ->success()
                ->seconds(4)
                ->send();
        }
    }
}
