<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Content extends Model
{
    protected $fillable = ['title', 'description', 'photo', 'is_active'];

    public function Pages()
    {
        return $this->morphMany(Page::class, 'source');
    }

    public static function getValidationRules($record = null): array
    {
        return [
            'title' => [
                'required',
                'max:100',
                Rule::unique('contents', 'title')->ignore($record)
            ],
        ];
    }

    protected static function booted()
    {
        parent::booted();

        // Event Listener
        static::saving(function ($content) {
            // Cek apakah ada perubahan pada field yang diisi
            if ($content->isDirty('title') || $content->isDirty('description') || $content->isDirty('is_active')) {
                self::resizePhotoIfNeeded($content);
                return true;
            }

            // Jika hanya photo yang berubah
            if ($content->isDirty('photo')) {
            self::resizePhotoIfNeeded($content);
                return true;
            }

            // Jika tidak ada perubahan apapun
            return false;
        });

        static::updating(function ($content) {
            if ($content->isDirty('photo')) {
                self::deletePhotoFile($content->getOriginal('photo'));
            }
        });

        static::deleting(function ($content) {
            self::deletePhotoFile($content->photo);
        });
    }

    protected static function resizePhotoIfNeeded($content)
    {
        // Cek apakah ada foto yang diupload
        if (!$content->photo) {
            return;
        }

        // Lokasi penyimpanan foto
        $fileLocation = storage_path('app/public/' . $content->photo);

        // Cek apakah file foto ada di lokasi pennyimpanan
        if (!file_exists($fileLocation)) {
            return;
        }

        $maxFileSize = 1024 * 1024;

        //Proses resize foto
        $manager = new ImageManager(new Driver());
        $photo = $manager->read($fileLocation);

        // Jika ukuran file lebih dari 1MB, lakukan resize
        if (filesize($fileLocation) > $maxFileSize) {
        $photo->scale(width: 800);
        $quality = 80; // Ukuran kualitas foto
        while (filesize($fileLocation) > $maxFileSize && $quality >= 30) {
            $photo->save($fileLocation, quality: $quality);
            clearstatcache(true, $fileLocation);
            $quality -= 5;
            }
        } else {
            // Untuk file kecil, tetap simpan ulang untuk memastikan konsistensi
            $photo->save($fileLocation, quality: 80);
        }
    }

    protected static function deletePhotoFile($photo)
    {
        // Cek apakah ada foto
        if (!$photo) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $photo);
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }
}
