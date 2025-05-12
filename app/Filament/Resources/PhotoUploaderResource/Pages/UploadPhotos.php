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
use Illuminate\Support\Facades\Log;

class UploadPhotos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PhotoUploaderResource::class;

    protected static string $view = 'filament.resources.photo-uploader-resource.pages.upload-photos';

    // Variabel untuk menyimpan data form
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
        // Validasi form
        $formData = $this->form->getState();

        // Debug form data
        Log::info('Form data:', $formData);

        // Dapatkan foto dari formData
        $photos = $formData['photos'] ?? [];

        // Jika tidak ada foto
        if (empty($photos)) {
            Notification::make()
                ->title('Tidak ada foto dipilih')
                ->danger()
                ->send();
            return;
        }

        // Log photos array
        Log::info('Photos array:', [
            'count' => count($photos),
            'content' => $photos
        ]);

        $count = 0;

        try {
            DB::beginTransaction();

            // Loop untuk setiap file
            foreach ($photos as $filePath) {
                Log::info('Processing file path:', [
                    'path' => $filePath
                ]);

                if (is_string($filePath) && !empty($filePath)) {
                    // Buat record foto baru
                    $photo = new Photo();
                    $photo->file_path = $filePath;
                    $photo->save();

                    Log::info('Photo created with ID: ' . $photo->id);
                    $count++;
                }
            }

            DB::commit();

            // Debug total di database
            $totalInDb = Photo::count();
            Log::info("Total photos in database: $totalInDb");

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

            // Log error
            Log::error('Error uploading photos: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Tampilkan notifikasi error
            Notification::make()
                ->title('Error')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
