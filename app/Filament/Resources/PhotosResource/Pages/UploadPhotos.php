<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use App\Models\Photo;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\DB;

class UploadPhotos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PhotosResource::class;

    protected static string $view = 'filament.resources.photos-resource.pages.upload-photos';

    public $photos = null;

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
                            ->helperText('Pilih minimal satu foto untuk upload.')
                    ]),

                Actions::make([
                    FormAction::make('upload')
                        ->label('Upload')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->color('success')
                        ->size('sm')
                        ->action('create')
                ])
                ->alignment(Alignment::Left)
            ]);
    }

    public function create(): void
    {
        // Ambil data form
        $data = $this->form->getState();

        // Validasi apakah ada foto yang dipilih
        if (empty($data['photos'])) {
            Notification::make()
                ->title('Error')
                ->body('Tidak ada foto yang dipilih. Silakan pilih minimal satu foto.')
                ->danger()
                ->send();
            return;
        }

        $count = 0;

        try {
            DB::beginTransaction();

            // Simpan setiap foto ke database
            foreach ($data['photos'] as $filePath) {
                if (is_string($filePath) && !empty($filePath)) {
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
            
            // Notifikasi gagal
            Notification::make()
                ->title('Error')
                ->body('Gagal menyimpan foto: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
