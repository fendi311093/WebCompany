<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'title',
        'description',
        'photo',
    ];

    protected static function booted()
    {
        parent::booted();

        // Event Lisener
        static::saved(function ($facility) {
            self::resizePhotoIfNeeded($facility);
        });

        static::deleting(function ($facility) {
            self::deletePhotoFile($facility->photo);
        });

        static::updating(function ($facility) {
            if ($facility->isDirty('photo')) {
                self::deletePhotoFile($facility->getOriginal('photo'));
            }
        });
    }

    // Resize ukuran foto
    protected static function resizePhotoIfNeeded($facility)
    {
        // Cek apakah ada photo
        if (!$facility->photo) {
            return;
        }

        $file = storage_path('app/public/' . $facility->photo);

        //Cek di lokasi foto
        if (!file_exists($file)) {
            return;
        }

        $maxFileSize = 1024 * 1024; // 1Mb

        // cek ukuran file, Kurang dari 1Mb tidak dilakukan proses resize
        if (filesize($file) <= $maxFileSize) {
            return;
        }

        // Proses resize file lebih dari sama dengan 1Mb
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file);
        $image->scale(width: 800);

        $quality = 80;
        while (filesize($file) > $maxFileSize && $quality >= 30) {
            $image->save($file, quality: $quality);
            clearstatcache(true, $file);
            $quality -= 5;
        }
    }

    // Hapus photo dari storage
    protected static function deletePhotoFile($photo)
    {
        if (!$photo) {
            return;
        }

        $file = storage_path('app/public/' . $photo);
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
