<?php

namespace App\Filament\Resources\PhotoUploaderResource\Pages;

use App\Filament\Resources\PhotoUploaderResource;
use App\Models\Photo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class UploadPhotos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PhotoUploaderResource::class;

    protected static string $view = 'filament.resources.photo-uploader-resource.pages.upload-photos';

    public $photos = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photos')
                    ->label('Pilih Foto (dapat multi-pilih)')
                    ->required()
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('Photos')
                    ->visibility('public')
                    ->downloadable()
            ]);
    }

    public function create(): void
    {
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
    }
}
