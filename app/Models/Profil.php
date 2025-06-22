<?php

namespace App\Models;

use App\Jobs\ResizePhotoJob;
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
            if ($profil->isDirty('photo')) {
                self::deletePhotoFile($profil->getOriginal('photo'));

                // Resize photo jika ukuran lebih dari 1MB
                $fileLocation = storage_path('app/public/' . $profil->photo);
                if (file_exists($fileLocation) && filesize($fileLocation) > 1024 * 1024) {
                    dispatch(new ResizePhotoJob($profil->id, 'Profil', 'photo'))->delay(now()->addMinutes(5));
                }
            }

            if ($profil->isDirty('logo')) {
                self::deleteLogoFile($profil->getOriginal('logo'));
            }

            // clear cache
            Cache::forget('source_ids_App\Models\Profil_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
            Cache::forget('dropdown_menu_page_options');
        });

        static::deleting(function ($profil) {
            // Cascade delete ke Page
            $profil->Pages()->delete();
            self::deletePhotoFile($profil->photo);
            self::deleteLogoFile($profil->logo);

            // clear cache
            Cache::forget('source_ids_App\Models\Profil_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
            Cache::forget('dropdown_menu_page_options');
        });
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

    public static function generateSafeFileName($profilName, $file)
    {
        // Ubah ke huruf besar lalu slug agar rapi dan aman
        $upperName = strtoupper($profilName);
        $safeName = strtoupper(\Illuminate\Support\Str::slug($upperName, '_'));
        $safeName = \Illuminate\Support\Str::limit($safeName, 50, '');

        // Validasi dan pastikan hanya ekstensi gambar tertentu yang diizinkan
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $guessed = $file->guessExtension();
        $extension = in_array($guessed, $allowedExtensions) ? ($guessed === 'jpeg' ? 'jpg' : $guessed) : 'jpg';

        // Tambahkan timestamp agar nama unik
        $timestamp = now()->format('dmy_His');

        return "{$safeName}_{$timestamp}.{$extension}";
    }
}
