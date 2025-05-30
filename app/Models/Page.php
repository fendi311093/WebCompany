<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['source_type', 'source_id', 'style_view', 'is_active'];

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
        if ($sourceType === 'App\Models\Profil') {
            return Profil::pluck('name_company', 'id')->toArray();
        }
        if ($sourceType === 'App\Models\Customer') {
            return Customer::pluck('name_customer', 'id')->toArray();
        }
        if ($sourceType === 'App\Models\Content') {
            return Content::pluck('title', 'id')->toArray();
        }
        return [];
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
