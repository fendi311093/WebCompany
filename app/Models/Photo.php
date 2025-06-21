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
        parent::booted();

        // Tidak pakai created karena sudah ada di CreatePhoto.php

        // Ketika model Photo diperbarui
        static::updated(function ($photo) {
            if ($photo->isDirty('file_path')) {
                $originalPath = $photo->getOriginal('file_path');
                if ($originalPath) {
                    self::deletePhotoFile($originalPath);
                }
                dispatch(new \App\Jobs\ResizePhotoJob($photo->id, 'Photo', 'file_path'))->delay(now()->addMinutes(5));
            }
        });

        static::deleted(function ($photo) {
            self::deletePhotoFile($photo->file_path);
        });
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
