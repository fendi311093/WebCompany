<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Photo;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class ListPhotos extends ListRecords
{
    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.list-photos';
    protected static ?string $title = '';

    public $photoToDelete = null;
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

    public function deletePhoto($hashedId): void
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        if (!$id) {
            Notification::make()
                ->title('Error')
                ->body('Invalid photo ID')
                ->danger()
                ->send();
            return;
        }

        $photo = Photo::find($id);

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
