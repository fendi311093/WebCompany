<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use App\Models\Photo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class UploadPhotos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.upload-photos';

    public $photos = null;
    public $isUploading = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        FileUpload::make('photos')
                            ->label('Photo')
                            ->required()
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('Photos')
                            ->visibility('public')
                            ->downloadable()
                            ->imageResizeMode('cover')
                    ])
            ]);
    }

    public function create(): void
    {
        $this->isUploading = true;

        // Ambil data form
        $formData = $this->form->getState();

        // Dapatkan array foto
        $photos = $formData['photos'] ?? [];

        // Jika tidak ada foto yang dipilih
        if (empty($photos)) {
            Notification::make()
                ->title('Tidak ada foto dipilih')
                ->danger()
                ->send();
            $this->isUploading = false;
            return;
        }

        $count = 0;

        try {
            DB::beginTransaction();

            // Loop untuk setiap file
            foreach ($photos as $filePath) {
                if (is_string($filePath) && !empty($filePath)) {
                    // Buat record foto baru
                    $photo = new Photo();
                    $photo->file_path = $filePath;
                    $photo->save();

                    $count++;
                }
            }

            DB::commit();

            // Notifikasi sukses
            Notification::make()
                ->title('Upload Berhasil!')
                ->body("$count foto berhasil disimpan ke database")
                ->success()
                ->send();

            // Reset form
            $this->photos = null;
            $this->form->fill();
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->body('Gagal menyimpan foto: ' . $e->getMessage())
                ->danger()
                ->send();
        }

        $this->isUploading = false;
    }
}
