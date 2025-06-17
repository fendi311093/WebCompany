<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class NavigationWeb extends Model
{
    protected $fillable = [
        'type',
        'title',
        'slug',
        'position',
        'is_active_page',
        'page_id',
        'is_active_link',
        'link',
    ];

    public function PagesRelation(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public static function getPages()
    {
        return Cache::remember('navigation_web_page_options', now()->addHour(), function () {
            return Page::query()
                ->select(['id', 'source_type', 'source_id'])
                ->with(['source:id,name_company,name_customer,title'])
                ->where('is_active', true)
                ->get()
                ->mapWithKeys(function ($page) {
                    $label = match ($page->source_type) {
                        'App\\Models\\Profil' => "PROFIL - {$page->source->name_company}",
                        'App\\Models\\Customer' => "CUSTOMER - {$page->source->name_customer}",
                        'App\\Models\\Content' => "CONTENT - {$page->source->title}",
                        default => 'Unknown Type'
                    };

                    return [$page->id => $label];
                })
                ->toArray();
        });
    }
}
