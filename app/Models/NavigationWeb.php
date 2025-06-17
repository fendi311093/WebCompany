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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_active_page == false) {
                $model->page_id = null;
            }

            if ($model->is_active_link == false) {
                $model->link = null;
            }
        });

        static::updating(function ($model) {
            if ($model->is_active_page == false) {
                $model->page_id = null;
            }

            if ($model->is_active_link == false) {
                $model->link = null;
            }
        });
    }

    //Validation unique title
    protected static function validateUniqueName($title, $ignoreId = null)
    {
        $normalizedValue = preg_replace('/\s+/', '', $title);
        $query = static::whereRaw('REPLACE(title, " ", "") = ?', [$normalizedValue]);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return !$query->exists();
    }

    //Validation rules
    public static function getValidationRules($record = null): array
    {
        return [
            'title' => [
                'required',
                'min:3',
                'regex:/^[^\s].*$/',
                'max:30',
                'unique:navigation_webs,title,' . $record?->id,
                fn($attribute, $value, $fail) => !self::validateUniqueName($value, $record?->id)
                    ? $fail("The navigation {$value} already exists ... !")
                    : null,
            ],
            'slug' => ['required'],
            'position' => ['required'],
            'type' => ['required'],
            'page_id' => ['required'],
            'link' => ['required', 'url', 'max:255'],
        ];
    }

    //Validation messages
    public static function getValidationMessages(): array
    {
        return [
            'title' => [
                'required' => 'The title is required. Please enter a title.',
                'min' => 'The title must be at least 3 characters.',
                'regex' => 'The title must start with a non-space character.',
                'max' => 'The title may not be greater than 30 characters.',
                'unique' => fn($state): string => "The navigation {$state} already exists. Please enter a different title."
            ],
            'slug' => [
                'required' => 'The slug is required. Please enter a slug.',
            ],
            'position' => [
                'required' => 'The position is required. Please select a position.',
            ],
            'type' => [
                'required' => 'The type is required. Please select a type.',
            ],
            'page_id' => [
                'required' => 'The page is required when page is active.',
            ],
            'link' => [
                'required' => 'Link is required when link is active.',
                'url' => 'Link must be a valid URL format. Example: https://example.com',
                'max' => 'Link may not be greater than 255 characters.',
            ]
        ];
    }

    //Menampilkan data page yang aktif untuk di option select di form
    public static function getPagesOptions()
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

    //Get used positions with cache
    public static function getUsedPositionsWithCache($type, $ignoreId = null)
    {
        static $cache = [];
        $cacheKey = $type . '-' . ($ignoreId ?? 'new');
        if (!isset($cache[$cacheKey])) {
            $query = self::where('type', $type);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $cache[$cacheKey] = $query->pluck('position')->toArray();
        }
        return $cache[$cacheKey];
    }
}
