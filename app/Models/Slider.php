<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Slider extends Model
{
    protected $fillable = [
        'slide_number',
        'photo_id',
        'is_active'
    ];

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }

    public static function getPhotoOptions(): array
    {
        return Cache::remember('slider_photo_options', now()->addDay(), function () {
            return Photo::query()
                ->select(['id', 'file_path'])
                ->orderByDesc('created_at')
                ->get()
                ->mapWithKeys(function ($photo) {
                    return [$photo->getRouteKey() => basename($photo->file_path)];
                })
                ->toArray();
        });
    }

    public static function getUsedPhotoIds($ignoreId = null): array
    {
        $query = self::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return $query->pluck('photo_id')->filter()->unique()->toArray();
    }

    public static function getUsedSlideNumbers($ignoreId = null): array
    {
        $query = self::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return $query->pluck('slide_number')->filter()->unique()->toArray();
    }
}
