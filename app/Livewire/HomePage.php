<?php

namespace App\Livewire;

use App\Models\Content;
use App\Models\Customer;
use App\Models\Profil;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Web Company')]
class HomePage extends Component
{
    public $customers;
    public $sliders;
    public $contents;

    public function mount()
    {
        $this->customers = Customer::all();
        $this->sliders = Slider::where('is_active', true)
            ->orderBy('slide_number', 'asc')
            ->with('photo') // Load relasi photo
            ->get();
        $this->contents = Content::where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.home-page', [
            'customers' => $this->customers,
            'sliders'   => $this->sliders,
            'contents'  => $this->contents,
        ]);
    }
}
