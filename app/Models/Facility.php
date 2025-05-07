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

    /**
     * Mendapatkan semua rules validasi untuk model Faciliy
     */
    public static function getValidationRules($record = null)
    {
        return [
            'title' => [
                'required',
                'min:5',
                'max:50',
                function ($attribute, $value, $fail) use ($record) {
                    if (!self::validateUniqueName($value, $record?->id)) {
                        $fail('This title already exists.');
                    }
                }
            ],
            'description' => [
                'required'
            ]
        ];
    }

    /**
     * Mendapatkan pesan validasi kustom
     */
    public static function getValidationMessages()
    {
        return [
            'title' => [
                'required' => 'The title field is required.',
                'min:5' => 'Title field must be at least 5 characters.',
                'max:50' => 'Title feild maximum 50 characters.'
            ],
            'description' => [
                'required' => 'The description field is required.'
            ]
        ];
    }

    /**
     * Validasi nama customer yang unik tanpa memperhatikan spasi
     */
    public static function validateUniqueName($title, $ignoreId = null)
    {
        $normalizedValue = preg_replace('/\s+/', '', $title);
        $query = static::whereRaw('REPLACE(title, " ", "") = ?', [$normalizedValue]);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return !$query->exists();
    }

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
