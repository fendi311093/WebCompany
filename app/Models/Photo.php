<?php

namespace App\Models;

use App\Jobs\ResizePhotoJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class Photo extends Model
{
    protected $fillable = [
        'file_path',
    ];

    public function slider(): HasOne
    {
        return $this->hasOne(Slider::class, 'photo_id');
    }

    protected static function booted()
    {
        parent::booted();

        // Tidak pakai created karena sudah ada di CreatePhoto.php

        // Ketika model Photo diperbarui
        static::updated(function ($photo) {
            if ($photo->isDirty('file_path')) {
                $originalPath = $photo->getOriginal('file_path');
                if ($originalPath) {
                    self::deletePhotoFile($originalPath);
                }

                // Resize photo jika ukuran lebih dari 1MB
                $fileLocation = storage_path('app/public/' . $photo->file_path);
                if (file_exists($fileLocation) && filesize($fileLocation) > 1024 * 1024) {
                    dispatch(new ResizePhotoJob($photo->id, 'Photo', 'file_path'))->delay(now()->addMinutes(5));
                }
            }

            // Clear cache
            Cache::forget('slider_photo_options');
        });

        static::deleted(function ($photo) {
            self::deletePhotoFile($photo->file_path);

            // Clear cache
            Cache::forget('slider_photo_options');
        });
    }

    // Hapus photo dari storage
    protected static function deletePhotoFile($filePath)
    {
        if (!$filePath) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $filePath);
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }

    public static function generateSafeFileName(TemporaryUploadedFile $file)
    {
        // Validasi MIME harus bertipe gambar
        if (!Str::startsWith($file->getMimeType(), 'image/')) {
            throw new \Exception('File yang diunggah harus berupa gambar.');
        }

        // Ambil nama asli
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Bersihkan nama file dari karakter aneh & batasi panjang
        $safeName = Str::slug($originalName, '_'); // gunakan underscore jika ingin lebih mirip aslinya
        $safeName = strtoupper(Str::limit($safeName, 50, ''));

        // Validasi ekstensi yang diizinkan
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $guessed = $file->guessExtension();
        $extension = in_array($guessed, $allowedExtensions) ? ($guessed === 'jpeg' ? 'jpg' : $guessed) : 'jpg';

        // Tambah timestamp untuk nama unik
        $timestamp = now()->format('dmy_His');

        return "{$safeName}_{$timestamp}.{$extension}";
    }
}
