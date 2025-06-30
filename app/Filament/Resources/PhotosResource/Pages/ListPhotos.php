<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use App\Models\Photo;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListPhotos extends Page
{
    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.list-photos';
    protected static ?string $title = '';

    public int $perPage = 10;

    public function mount(): void
    {
        $this->perPage = request('perPage', 10);
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
                ->send();
        }
    }

    public function getPhotos()
    {
        return \App\Models\Photo::latest()->paginate($this->perPage)->withQueryString();
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('upload')
            //     ->label('Upload Multiple')
            //     ->icon('heroicon-o-cloud-arrow-up')
            //     ->color('warning')
            //     ->url(route('filament.admin.resources.photos.upload')),
            // Actions\CreateAction::make()
            //     ->label('New photo'),
        ];
    }
}
