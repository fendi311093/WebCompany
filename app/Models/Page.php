<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = ['source', 'is_active'];

    public function source()
    {
        return $this->morphTo();
    }

    public function HeaderButtons(): HasMany
    {
        return $this->hasMany(Page::class, 'page_id');
    }
}
