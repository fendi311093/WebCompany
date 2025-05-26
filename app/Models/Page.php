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

    public static function getSourceOptions($sourceType): array
    {
        // Get existing source_ids for the selected type
        $existingSourceId = Page::where('source_type', $sourceType)
            ->pluck('source_id')
            ->toArray();

        if ($sourceType === 'App\Models\Profil') {
            return Profil::whereNotIn('id', $existingSourceId)
                ->pluck('name_company', 'id')
                ->toArray();
        }
        if ($sourceType === 'App\Models\Customer') {
            return Customer::whereNotIn('id', $existingSourceId)
                ->pluck('name_customer', 'id')
                ->toArray();
        }
        if ($sourceType === 'App\Models\Content') {
            return Content::whereNotIn('id', $existingSourceId)
                ->pluck('title', 'id')
                ->toArray();
        }
        return [];
    }
}
