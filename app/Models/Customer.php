<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Customer extends Model
{
    protected $fillable = [
        'name_customer',
        'logo',
        'is_active'
    ];

    public function Pages()
    {
        return $this->morphMany(Page::class, 'source');
    }

    /**
     * Mendapatkan semua rules validasi untuk model Customer
     */
    public static function getValidationRules($record = null)
    {
        return [
            'name_customer' => [
                'required',
                'min:5',
                'max:50',
                function ($attribute, $value, $fail) use ($record) {
                    if (!self::validateUniqueName($value, $record?->id)) {
                        $fail('This customer name already exists.');
                    }
                }
            ]
        ];
    }

    /**
     * Mendapatkan pesan validasi kustom
     */
    public static function getValidationMessages()
    {
        return [
            'required' => 'The customer field is required.',
            'min:5' => 'Customer name must be at least 5 characters.',
            'max:50' => 'Customer name maximum 50 characters.'
        ];
    }

    /**
     * Validasi nama customer yang unik tanpa memperhatikan spasi
     */
    public static function validateUniqueName($customerName, $ignoreId = null)
    {
        $normalizedValue = preg_replace('/\s+/', '', $customerName);
        $query = static::whereRaw('REPLACE(name_customer, " ", "") = ?', [$normalizedValue]);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return !$query->exists();
    }

    // Mutator to capitalize the name_customer field
    // public function setNameCustomerAttribute($value)
    // {
    //     $this->attributes['name_customer'] = ucwords(strtolower($value));
    // }

    protected static function booted()
    {
        parent::booted();

        static::saved(function ($customer) {
            if ($customer->isDirty('logo')) {
                self::deleteLogoFile($customer->getOriginal('logo'));
            }

            // clear cache
            Cache::forget('source_ids_App\Models\Customer_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
            Cache::forget('dropdown_menu_page_options');
        });

        static::deleting(function ($customer) {
            // Cascade delete ke Page
            $customer->Pages()->delete();
            self::deleteLogoFile($customer->logo);

            // clear cache
            Cache::forget('source_ids_App\Models\Customer_' . config('app.env'));
            Cache::forget('page_options_' . config('app.env'));
            Cache::forget('dropdown_menu_page_options');
        });
    }

    /**
     * Hapus file logo dari storage.
     */
    protected static function deleteLogoFile($logo)
    {
        if (!$logo) {
            return;
        }

        $file = storage_path('app/public/' . $logo);
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
