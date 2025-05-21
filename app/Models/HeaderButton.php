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
        'title',
        'slug',
        'position',
        'page_id',
        'is_active',
        'icon',
    ];

    public function Pages(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public function dropdownMenus(): HasMany
    {
        return $this->hasMany(DropdownMenu::class, 'headerButton_id', 'id');
    }

    public static function getUsedPosition($value): bool
    {
        return Self::where('position', $value)->exists();
    }

    public static function ValidationRules($record = null): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:15',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('header_buttons', 'slug')->ignore($record),
            ],
            'position' => [
                'required',
            ],
        ];
    }

    public static function getValidationMessages(): array
    {
        return [
            'title' => [
                'required' => 'The title field is required.',
                'string' => 'The title field must be a string.',
                'max' => 'The title field must be less than 15 characters.',
                'regex' => 'The title field must contain only letters and spaces.',
            ]
        ];
    }
}
