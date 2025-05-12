<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model
{
    protected $fillable = [
        'slide_number',
        'gallery_id',
        'is_active'
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }
}
