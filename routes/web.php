<?php

use App\Livewire\AboutUs;
use App\Livewire\HomePage;
use App\Models\HeaderButton;
use Illuminate\Support\Facades\Route;
use App\Livewire\ViewDinamis;

Route::get('/', HomePage::class)->name('Home');

// Route dinamis untuk header button
Route::get('/{slug}', ViewDinamis::class)->where('slug', '^[a-zA-Z0-9-_]+$');
