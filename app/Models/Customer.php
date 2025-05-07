<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Customer extends Model
{
    protected $fillable = [
        'name_customer',
        'logo'
    ];

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
            self::optimizeLogoIfNeeded($customer);
        });

        static::deleting(function ($customer) {
            self::deleteLogoFile($customer->logo);
        });

        static::updating(function ($customer) {
            if ($customer->isDirty('logo')) {
                self::deleteLogoFile($customer->getOriginal('logo'));
            }
        });
    }

    /**
     * Optimasi file logo jika melebihi batas ukuran.
     */
    protected static function optimizeLogoIfNeeded($customer)
    {
        if (!$customer->logo) {
            return;
        }

        $file = storage_path('app/public/' . $customer->logo);

        if (!file_exists($file)) {
            return;
        }

        $maxFileSize = 1024 * 1024; // 1 MB

        if (filesize($file) <= $maxFileSize) {
            return;
        }

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file);
        $image->scale(width: 800);

        $quality = 80;
        while (filesize($file) > $maxFileSize && $quality >= 30) {
            $image->save($file, quality: $quality);
            clearstatcache(true, $file);
            $quality -= 5;
        }
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
