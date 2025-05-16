<?php

use App\Livewire\AboutUs;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/About-Us', AboutUs::class);
