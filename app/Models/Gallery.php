<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    protected $fillable = [
        'description',
        'is_published'
    ];

    public function sliders(): HasMany
    {
        return $this->hasMany(Slider::class, 'gallery_id');
    }
}
