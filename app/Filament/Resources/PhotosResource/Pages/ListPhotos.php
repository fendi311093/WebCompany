<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use App\Models\Photo;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\WithPagination;

class ListPhotos extends Page
{
    use WithPagination;

    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.list-photos';
    protected static ?string $title = '';

    public function getPhotos()
    {
        return Photo::latest()->paginate(10);
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
}
