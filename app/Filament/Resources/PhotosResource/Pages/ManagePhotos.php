<?php

namespace App\Filament\Resources\PhotosResource\Pages;

use App\Filament\Resources\PhotosResource;
use App\Models\Photo;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ManagePhotos extends ManageRecords
{
    protected static string $resource = PhotosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Model {
                    // Debug data yang diterima
                    Log::info('Create Action Data:', $data);

                    // Jika tidak ada file, return null
                    if (empty($data['file_path'])) {
                        Log::warning('No files uploaded');
                        return new Photo();
                    }

                    // Simpan foto pertama untuk return value
                    $firstPhoto = null;

                    try {
                        // Cek apakah file_path adalah array atau string tunggal
                        if (is_array($data['file_path'])) {
                            // Loop semua file dan buat record terpisah untuk masing-masing
                            foreach ($data['file_path'] as $filePath) {
                                Log::info('Creating photo with path: ' . $filePath);

                                $photo = Photo::create([
                                    'file_path' => $filePath,
                                    // Jika ada gallery_id, uncomment baris berikut
                                    // 'gallery_id' => $data['gallery_id'] ?? null,
                                ]);

                                Log::info('Photo created with ID: ' . $photo->id);

                                // Simpan foto pertama sebagai return value
                                if (!$firstPhoto) {
                                    $firstPhoto = $photo;
                                }
                            }
                        } else {
                            // Jika file_path adalah string tunggal (satu file)
                            Log::info('Creating single photo with path: ' . $data['file_path']);

                            $firstPhoto = Photo::create([
                                'file_path' => $data['file_path'],
                                // Jika ada gallery_id, uncomment baris berikut
                                // 'gallery_id' => $data['gallery_id'] ?? null,
                            ]);

                            Log::info('Single photo created with ID: ' . $firstPhoto->id);
                        }
                    } catch (\Exception $e) {
                        Log::error('Error creating photos: ' . $e->getMessage());
                        Log::error($e->getTraceAsString());
                        throw $e;
                    }

                    return $firstPhoto ?? new Photo();
                }),
        ];
    }
}
