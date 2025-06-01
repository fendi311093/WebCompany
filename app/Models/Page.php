<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    protected $fillable = ['source_type', 'source_id', 'style_view', 'is_active'];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope('withSource', function ($query) {
            $query->with('source');
        });

        static::saved(function () {
            Cache::forget('page_options_' . config('app.env'));
        });

        static::deleted(function () {
            Cache::forget('page_options_' . config('app.env'));
        });
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function HeaderButtons(): HasMany
    {
        return $this->hasMany(HeaderButton::class, 'page_id', 'id');
    }

    public function dropdownMenus(): HasMany
    {
        return $this->hasMany(DropdownMenu::class, 'page_id', 'id');
    }

    // ambil semua source_id dan tampilkan nama source
    public static function getAllSourceIds($sourceType): array
    {
        static $cache = [];

        if (!isset($cache[$sourceType])) {
            $cache[$sourceType] = Cache::remember("source_ids_{$sourceType}_" . config('app.env'), now()->addHour(), function () use ($sourceType) {
                return match ($sourceType) {
                    'App\Models\Profil' => Profil::pluck('name_company', 'id')
                        ->toArray(),
                    'App\Models\Customer' => Customer::where('is_active', true)
                        ->pluck('name_customer', 'id')
                        ->toArray(),
                    'App\Models\Content' => Content::where('is_active', true)
                        ->pluck('title', 'id')
                        ->toArray(),
                    default => []
                };
            });
        }

        return $cache[$sourceType] ?? [];
    }

    // ambil semua source_id yang sudah dipakai di Page untuk 
    public static function getUsedSourceIds($sourceType): array
    {
        return Page::where('source_type', $sourceType)->pluck('source_id')->toArray();
    }

    public static function createPageFromNavigation(array $data)
    {
        $page = self::create([
            'source_type' => $data['source_type'],
            'source_id' => $data['source_id'],
            'style_view' => $data['style_view'],
            'is_active' => $data['is_active'] ?? true,
        ]);
        return $page->id;
    }
}
