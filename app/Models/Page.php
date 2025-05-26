<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['source_type', 'source_id', 'is_active'];

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

    // public static function getSourceOptions($sourceType): array
    // {
    //     // Ambil semua source_id yang sudah dipakai di Page untuk tipe ini
    //     $usedSourceIds = Page::where('source_type', $sourceType)->pluck('source_id')->toArray();

    //     if ($sourceType === 'App\\Models\\Profil') {
    //         return \App\Models\Profil::whereNotIn('id', $usedSourceIds)
    //             ->pluck('name_company', 'id')
    //             ->toArray();
    //     }
    //     if ($sourceType === 'App\\Models\\Customer') {
    //         return \App\Models\Customer::whereNotIn('id', $usedSourceIds)
    //             ->pluck('name_customer', 'id')
    //             ->toArray();
    //     }
    //     if ($sourceType === 'App\\Models\\Content') {
    //         return \App\Models\Content::whereNotIn('id', $usedSourceIds)
    //             ->pluck('title', 'id')
    //             ->toArray();
    //     }
    //     return [];
    // }

    // ambil semua source_id dan tampilkan nama source
    public static function getAllSourceIds($sourceType): array
    {
        return Page::where('source_type', $sourceType)->get()->mapWithKeys(function ($page){
            $label = match ($page->source_type) {
                'App\Models\Profil' => $page->source->name_company,
                'App\Models\Customer' => $page->source->name_customer,
                'App\Models\Content' => $page->source->title,
                default => 'Unknown'
            };
            return [$page->id => $label];
        })->toArray();
    }
}
