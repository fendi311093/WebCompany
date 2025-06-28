<?php

namespace App\Models;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

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
        return $query->pluck('photo_id')
            ->filter()
            ->unique()
            ->map(function ($photoId) {
                return Hashids::encode($photoId);
            })
            ->toArray();
    }

    public static function getUsedSlideNumbers($ignoreId = null): array
    {
        $query = self::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return $query->pluck('slide_number')
            ->filter()
            ->unique()
            ->toArray();
    }

    // Proses HashID untuk URL
    // Mengenkripsi ID asli menjadi string terenkripsi
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    // Mendekripsi ID terenkripsi kembali ke ID asli
    public function resolveRouteBinding($value, $field = null)
    {
        $id = Hashids::decode($value);
        return $this->find($id[0] ?? null);
    }

    // Mendapatkan ID terenkripsi untuk digunakan di tempat lain
    public function getHashedId(): string
    {
        return Hashids::encode($this->id);
    }

    // Mencari record berdasarkan ID terenkripsi
    public static function findByHashedId($hashedId): ?self
    {
        if (!$hashedId) {
            return null;
        }

        $id = Hashids::decode($hashedId)[0] ?? null;
        return $id ? self::find($id) : null;
    }
}
