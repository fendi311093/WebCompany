<?php

namespace App\Models;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class HeaderButton extends Model
{
    protected $fillable = [
        'page_id',
        'type_button',
        'position_header',
        'position_sub_header',
        'name_button',
        'url',
        'is_active_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->type_button == 1) {
                $model->position_sub_header = 0;
            } elseif ($model->type_button == 2) {
                $model->position_header = 0;
            }
        });

        static::updating(function ($model) {
            if ($model->type_button == 1) {
                $model->position_sub_header = 0;
            } elseif ($model->type_button == 2) {
                $model->position_header = 0;
            }
        });
    }

    public function Pages(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public static function getUsedPosition($value): bool
    {
        return Self::where('position', $value)->exists();
    }

    public static function getValidationRules($record = null): array
    {
        return [
            'type_button' => [
                'required',
            ],
            'page_id' => [
                'required',
            ],
            'name_button' => [
                'required',
                'min:3',
                'regex:/^[^\s].*$/',
                'max:30',
                'unique:header_buttons,name_button,' . $record?->id,
                fn($attribute, $value, $fail) => !self::validateUniqueName($value, $record?->id)
                    ? $fail("The name button {$value} already exists ... !")
                    : null,
            ]
        ];
    }

    public static function getValidationMessages(): array
    {
        return [
            'type_button' => [
                'required' => 'The type button is required, Please select type button',
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
}
