<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

use function PHPUnit\Framework\fileExists;

class Profil extends Model
{
    protected $fillable = [
        'name_company',
        'address',
        'phone',
        'logo',
        'photo',
        'description'
    ];

    public function Pages()
    {
        return $this->morphMany(Page::class, 'source');
    }

    public static function getValidationRules($record = null)
    {
        return [
            'name_company' => [
                'required',
                'min:5',
                'max:50',
            ],
            'address' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'description' => [
                'required'
            ],
        ];
    }

    protected static function booted()
    {
        parent::booted();

        // Event Lisener
        static::saved(function ($profil) {
            self::resizePhotoIfNeeded($profil);
            self::resizeLogoIfNeeded($profil);

            // clear cache
            Cache::forget('source_ids_App\Models\Profil_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
        });

        static::updating(function ($profil) {
            if ($profil->isDirty('photo')) {
                self::deletePhotoFile($profil->getOriginal('photo'));
            }
            if ($profil->isDirty('logo')) {
                self::deleteLogoFile($profil->getOriginal('logo'));
            }
        });

        static::deleting(function ($profil) {
            // Cascade delete ke Page
            $profil->Pages()->delete();
            self::deletePhotoFile($profil->photo);
            self::deleteLogoFile($profil->logo);

            // clear cache
            Cache::forget('source_ids_App\Models\Profil_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
        });
    }

    // Resize ukuran foto
    protected static function resizePhotoIfNeeded($profil)
    {
        //Cek di field input apakah ada foto
        if (!$profil->photo) {
            return;
        }

        //Lokasi foto
        $fileLocation = storage_path('app/public/' . $profil->photo);

        //Cek apakah ada foto di lokasi penyimpanan
        if (!file_exists($fileLocation)) {
            return;
        }

        $maxFileSize = 1024 * 1024; //1Mb

        //Ukuran file dibawah 1Mb tidak dilakukan proses resize
        if (filesize($fileLocation) <= $maxFileSize) {
            return;
        }

        //Proses resize
        $manager = new ImageManager(new Driver());
        $photo = $manager->read($fileLocation);
        $photo->scale(width: 800);

        $quality = 80;
        while (filesize($fileLocation) > $maxFileSize && $quality >= 30) {
            $photo->save($fileLocation, quality: $quality);
            clearstatcache(true, $fileLocation);
            $quality -= 5;
        }
    }

    // Resize ukuran logo
    protected static function resizeLogoIfNeeded($profil)
    {
        //Cek di field input apakah ada logo
        if (!$profil->logo) {
            return;
        }

        //Lokasi logo
        $fileLocation = storage_path('app/public/' . $profil->logo);

        //Cek apakah ada logo di lokasi penyimpanan
        if (!file_exists($fileLocation)) {
            return;
        }

        $maxFileSize = 1024 * 1024; //1Mb

        //Ukuran file dibawah 1Mb tidak dilakukan proses resize
        if (filesize($fileLocation) <= $maxFileSize) {
            return;
        }

        //Proses resize
        $manager = new ImageManager(new Driver());
        $logo = $manager->read($fileLocation);
        $logo->scale(width: 800);

        $quality = 80;
        while (filesize($fileLocation) > $maxFileSize && $quality >= 30) {
            $logo->save($fileLocation, quality: $quality);
            clearstatcache(true, $fileLocation);
            $quality -= 5;
        }
    }

    //Hapus foto dari storage
    protected static function deletePhotoFile($photo)
    {
        //Cek apakah ada foto
        if (!$photo) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $photo);
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }

    //Hapus logo dari storage
    protected static function deleteLogoFile($logo)
    {
        //Cek apakah ada logo
        if (!$logo) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $logo);
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }
    }
}
