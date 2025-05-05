<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Customer extends Model
{
    protected $fillable = [
        'name_customer',
        'logo'
    ];

    protected static function booted()
    {
        parent::booted();

        static::saved(function ($customer) {
            // Jika tidak ada logo, keluarkan segera.
            if (!$customer->logo) {
                return;
            }

            $file = storage_path('app/public/' . $customer->logo);

            // Pastikan file logo ada.
            if (!file_exists($file)) {
                return;
            }

            $maxFileSize = 1024 * 1024; // 1 MB

            // Jika ukuran file sudah di bawah atau sama dengan batas, tidak perlu optimasi.
            if (filesize($file) <= $maxFileSize) {
                return;
            }

            // Buat instance ImageManager dengan driver yang sesuai (misalnya GD atau Imagick)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            // Ubah ukuran gambar sehingga lebarnya 800 piksel (proporsional)
            $image->scale(width: 800);

            $quality = 80;
            // Lakukan penyimpanan dan kompresi hingga ukuran file di bawah batas atau kualitas mencapai 30
            while (filesize($file) > $maxFileSize && $quality >= 30) {
                $image->save($file, quality: $quality);
                clearstatcache(true, $file); // Refresh informasi file
                $quality -= 5;
            }
        });

        static::deleting(function ($customer) {
            if ($customer->logo) {
                $file = storage_path('app/public/' . $customer->logo);
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        });

        // Hapus file lama jika logo diganti
        static::updating(function ($customer) {
            if ($customer->isDirty('logo')) { // jika field logo berubah
                $oldLogo = $customer->getOriginal('logo');
                if ($oldLogo) {
                    $oldFile = storage_path('app/public/' . $oldLogo);
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            }
        });
    }
}
