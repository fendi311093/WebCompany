<?php

namespace App\Models;

use App\Jobs\ResizePhotoJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Content extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'photo', 'is_active'];

    public function Pages()
    {
        return $this->morphMany(Page::class, 'source');
    }

    public static function getValidationRules($record = null)
    {
        return [
            'title' => [
                'required',
                'min:3',
                'regex:/^[^\s].*$/',
                'unique:contents,title,' . $record?->id,
                'max:50',
                fn($attribute, $value, $fail) => !self::validateUniqueName($value, $record?->id)
                    ? $fail("The title content {$value} already exists ... !")
                    : null,
            ],
            'description' => ['required'],
        ];
    }

    public static function getValidationMessages()
    {
        return [
            'title' => [
                'required' => 'The title content is required ...!',
                'min'   => 'The title content must be at least 3 characters ...!',
                'max' => 'The title content must not exceed 50 characters ...!',
                'regex' => 'The title content must not start with a space ...!',
                'unique' => fn($state): string => "The title content {$state} already exists ... !"
            ],
            'description' => ['required' => 'The description content is required...!']
        ];
    }

    protected static function validateUniqueName($title, $ignoreId = null)
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

        // Event Listener
        static::saved(function ($content) {
            if ($content->isDirty('photo')) {
                self::deletePhotoFile($content->getOriginal('photo'));

                // Resize photo jika ukuran lebih dari 1MB
                $fileLocation = storage_path('app/public/' . $content->photo);
                if (file_exists($fileLocation) && filesize($fileLocation) > 1024 * 1024) {
                    dispatch(new ResizePhotoJob($content->id, 'Content', 'photo'))->delay(now()->addMinutes(5));
                }
            }

            // clear cache
            Cache::forget('source_ids_App\Models\Content_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
        });

        // static::updating(function ($content) {});

        static::deleting(function ($content) {

            // Cascade delete ke Page karena relasi morphMany
            $content->Pages()->delete();

            self::deletePhotoFile($content->photo);

            // clear cache
            Cache::forget('source_ids_App\Models\Content_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
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

    public static function generateSafeFileName($contentName, $file)
    {
        // Ubah ke huruf besar lalu slug agar rapi dan aman
        $upperName = strtoupper($contentName);
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
