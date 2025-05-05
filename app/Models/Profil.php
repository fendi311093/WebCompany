<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'name_company',
        'address',
        'phone',
        'photo',
        'description'
    ];
}
