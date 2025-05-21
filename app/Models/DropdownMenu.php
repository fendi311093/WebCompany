<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DropdownMenu extends Model
{
    protected $fillable = ['title', 'slug', 'headerButton_id', 'page_id', 'is_active'];

    public function headerButton(): BelongsTo
    {
        return $this->belongsTo(HeaderButton::class, 'headerButton_id', 'id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
}
