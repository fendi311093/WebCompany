<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

        static::saved(function ($facility) {
            // Jika tidak ada logo, keluarkan segera
            if (!$facility->photo) {
                return;
            }

            $file = storage_path('app/public/' . $facility->phoot);

            // cek file logo ada
            if (!file_exists($file)) {
                return;
            }

            $maxFileSize = 1024 * 1024; // 1Mb

            // Jika ukuran file sudah dibawah atau sama dengan 1Mb, tidak perlu rezise
            if (filesize($file) <= $maxFileSize) {
                return;
            }

            // Pakai Library Intervention proses rezise
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            // ubah ukuran jadi 800 pixel
            $image->scale(width: 800);

            $quality = 80;
        });
    }
}
