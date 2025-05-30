<?php

namespace App\Models;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class HeaderButton extends Model
{
    protected $fillable = [
        'name_button',
        'slug',
        'position',
        'page_id',
        'is_active_button',
        'is_active_url',
        'url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model['is_active_url']) || $model['is_active_url'] == 0) {
                $model['url'] = null;
            }
        });

        static::updating(function ($model) {
            if (empty($model['is_active_url']) || $model['is_active_url'] == 0) {
                $model['url'] = null;
            }
        });
    }

    public function Pages(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public function dropdownButton(): HasMany
    {
        return $this->hasMany(DropdownMenu::class, 'header_button_id', 'id');
    }

    public static function getUsedPosition()
    {
        return Self::pluck('position')->toArray();
    }

    public static function getValidationRules($record = null): array
    {
        return [
            'name_button' => [
                'required',
                'min:3',
                'regex:/^[^\s].*$/',
                'max:30',
                'unique:header_buttons,name_button,' . $record?->id,
                fn($attribute, $value, $fail) => !self::validateUniqueName($value, $record?->id)
                    ? $fail("The name button {$value} already exists ... !")
                    : null,
            ],
            'slug' => [
                'required',
            ],
            'position' => [
                'required',
            ],
            'page_id' => [
                'required',
            ],
            'url' => [
                'required',
                'url',
                'max:255',
            ]
        ];
    }

    public static function getValidationMessages(): array
    {
        return [
            'slug' => [
                'required' => 'The slug is required, Please enter slug',
            ],
            'position' => [
                'required' => 'The position is required, Please select position',
            ],
            'page_id' => [
                'required' => 'The page is required, Please select page'
            ],
            'name_button' => [
                'required' => 'The name button is required, Please enter name button',
                'min' => 'The name button must be at least 3 characters',
                'regex' => 'The name button must not start with a space',
                'max' => 'The name button must not exceed 30 characters',
                'unique' => fn($state): string => "The name {$state} already exists"
            ],
            'url' => [
                'required' => 'The URL is required. Please enter URL',
                'url' => 'The URL format is invalid',
                'max' => "The URL must not exceed 255 characters"
            ]
        ];
    }

    protected static function validateUniqueName($name, $ignoreId = null)
    {
        $normalizedValue = preg_replace('/\s+/', '', $name);
        $query = static::whereRaw('REPLACE(name_button, " ", "") = ?', [$normalizedValue]);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return !$query->exists();
    }

    public static function getPageOptions(): array
    {
        return Page::with('source')->get()->mapWithKeys(function ($page) {
            $label = match ($page->source_type) {
                'App\Models\Profil' => $page->source->name_company,
                'App\Models\Customer' => $page->source->name_customer,
                'App\Models\Content' => $page->source->title,
                default => 'Unknown'
            };
            return [$page->id => $label];
        })->toArray();
    }

    public static function getUsedPageIds(): array
    {
        return Self::pluck('page_id')->toArray();
    }

    public function scopeSearchByPageTitle(Builder $query, string $search): Builder
    {
        $pageOptions = static::getPageOptions();
        $pageIds = collect($pageOptions)
            ->filter(fn($title) => str_contains(strtolower($title), strtolower($search)))
            ->keys()
            ->toArray();

        return $query->whereIn('page_id', $pageIds);
    }

    public function getPageLabelAttribute()
    {
        if ($this->Pages && $this->Pages->source) {
            switch ($this->Pages->source_type) {
                case 'App\\Models\\Profil':
                    return $this->Pages->source->name_company;
                case 'App\\Models\\Customer':
                    return $this->Pages->source->name_customer;
                case 'App\\Models\\Content':
                    return $this->Pages->source->title;
            }
        }
        return 'Unknown Page';
    }
}
