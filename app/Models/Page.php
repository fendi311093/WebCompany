<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['source', 'is_active'];

    public function source()
    {
        return $this->morphTo();
    }
}
