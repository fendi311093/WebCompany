<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['title', 'description', 'photo', 'is_active'];

    public function Pages()
    {
        return $this->morphMany(Page::class, 'source');
    }
}
