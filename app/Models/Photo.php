<?php

namespace App\Models;

use App\Jobs\ResizePhotoJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        parent::boot();

        static::updated(function ($photo) {
            if ($photo->isDirty('file_path')) {
                self::deletePhotoFile($photo->getOriginal('file_path'));
                dispatch(new ResizePhotoJob($photo->id, 'Photo', 'file_path'))->delay(now()->addMinutes(5));
            }
        });

        static::deleted(function ($photo) {
            self::deletePhotoFile($photo->file_path);
        });
    }

    // Resize ukuran foto
    protected static function resizePhotoIfNeeded($photo)
    {
        // Cek apakah ada photo
        if (!$photo->file_path) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $photo->file_path);

        // Cek di lokasi foto
        if (!file_exists($fileLocation)) {
            return;
        }

        $maxFileSize = 1024 * 1024; // 1Mb

        // Cek ukuran file, Kurang dari 1Mb tidak dilakukan proses resize
        if (filesize($fileLocation) <= $maxFileSize) {
            return;
        }

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($fileLocation);
        $image->scale(width: 800);

        $quality = 80;
        while (filesize($fileLocation) > $maxFileSize && $quality >= 30) {
            $image->save($fileLocation, quality: $quality);
            clearstatcache(true, $fileLocation);
            $quality -= 5;
        }
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
}
