<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Photo extends Model
{
    protected $fillable = [
        'file_path',
        'gallery_id'
    ];


    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    public function slider(): HasOne
    {
        return $this->hasOne(Slider::class, 'photo_id');
    }

    protected static function booted()
    {
        parent::boot();

        static::created(function ($photo) {
            self::resizePhotoIfNeeded($photo);
        });

        static::deleted(function ($photo) {
            self::deletePhotoFile($photo);
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
    protected static function deletePhotoFile($photo)
    {
        if (!$photo) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $photo->file_path);
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }
}
