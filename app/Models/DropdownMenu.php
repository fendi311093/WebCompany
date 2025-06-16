<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DropdownMenu extends Model
{
    protected $fillable = ['title', 'slug', 'headerButton_id', 'position', 'page_id', 'is_active'];

    public function headerButton(): BelongsTo
    {
        return $this->belongsTo(HeaderButton::class, 'headerButton_id', 'id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

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

    public static function getValidationRules($record = null): array
    {
        return [
            'title' => [
                'required',
                'min:3',
                'regex:/^[^\s].*$/',
                'max:30',
                'unique:dropdown_menus,title,' . $record?->id,
                fn($attribute, $value, $fail) => !self::validateUniqueName($value, $record?->id)
                    ? $fail("The dropdown menu {$value} already exists ... !")
                    : null,
            ],
            'slug' => [
                'required',
            ],
            'headerButton_id' => [
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
            'title' => [
                'required' => 'The title is required. Please enter a title.',
                'min' => 'The title must be at least 3 characters.',
                'regex' => 'The title must start with a non-space character.',
                'max' => 'The title may not be greater than 30 characters.',
                'unique' => fn($state): string => "The dropdown menu {$state} already exists. Please enter a different title."
            ],
            'slug' => [
                'required' => 'The slug is required. Please enter a slug.',
            ],
            'headerButton_id' => [
                'required' => 'The header button is required. Please select a header button.',
            ],
            'position' => [
                'required' => 'The position is required. Please select a position.',
            ],
            'page_id' => [
                'required' => 'The page is required. Please select a page.',
            ],
            'url' => [
                'required' => 'The URL is required. Please enter a valid URL.',
                'url' => 'The URL must be a valid URL format. Example: https://example.com',
                'max' => 'The URL may not be greater than 255 characters.',
            ]
        ];
    }

    protected static function validateUniqueName($title, $ignoreId = null)
    {
        $normalizedValue = preg_replace('/\s+/', '', $title);
        $query = static::whereRaw('REPLACE(title, " ", "") = ?', [$normalizedValue]);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return !$query->exists();
    }

    public static function getPageOptions()
    {
        static $pageOptions = null;

        if ($pageOptions === null) {
            $pageOptions = Cache::remember('dropdown_menu_page_options', now()->addHour(), function () {
                return Page::with(['source'])
                    ->where('is_active', true)
                    ->get()
                    ->mapWithKeys(function ($page) {
                        $label = match ($page->source_type) {
                            'App\Models\Profil' => 'Profil - ' . $page->source->name_company,
                            'App\Models\Customer' => 'Customer - ' . $page->source->name_customer,
                            'App\Models\Content' => 'Content - ' . $page->source->title,
                            default => 'Unknown',
                        };
                        return [$page->id => $label];
                    })
                    ->toArray();
            });
        }
        return $pageOptions;
    }

    public static function getHeaderOptions()
    {
        // Static cache untuk 1 lifecycle PHP (misalnya selama 1 request di Laravel)
        static $headerOptions = null;

        if ($headerOptions !== null) {
            return $headerOptions;
        }

        // Gunakan cache untuk menyimpan hasil query selama 1 jam
        $headerOptions = Cache::remember('dropdown_menu_header_options', now()->addHour(), function () {
            return HeaderButton::where('is_active_button', true)
                ->pluck('name_button', 'id')
                ->toArray();
        });

        return $headerOptions;
    }


    public static function validateField($value, $fieldName, $record = null): bool
    {
        static $usedValues = [];

        // Jika sedang edit dan nilai tidak berubah, tidak perlu validasi
        if ($record && $value == $record->{$fieldName}) {
            return false;
        }

        // Buat cacheKey berdasarkan field dan ID record (atau 'new')
        $cacheKey = $fieldName . '_' . ($record->id ?? 'new');

        // Ambil nilai-nilai field dari cache lokal
        if (!isset($usedValues[$cacheKey])) {
            $usedValues[$cacheKey] = self::query()
                ->when($record?->id, fn($q) => $q->where('id', '!=', $record->id))
                ->pluck($fieldName)
                ->all(); // Lebih ringan dari toArray()
        }

        // Cek apakah nilai sudah digunakan
        return in_array($value, $usedValues[$cacheKey]);
    }


    // Wrapper functions untuk backward compatibility
    public static function validatePosition($value, $record = null): bool
    {
        return self::validateField($value, 'position', $record);
    }

    public static function validateHeaderButton($value, $record = null): bool
    {
        return self::validateField($value, 'headerButton_id', $record);
    }

    public static function validatePage($value, $record = null): bool
    {
        return self::validateField($value, 'page_id', $record);
    }
}
