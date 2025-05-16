<?php

namespace App\Livewire;

use App\Models\Profil;
use Livewire\Component;

class AboutUs extends Component
{
    public $profil;

    public function mount()
    {
        $this->profil = Profil::first();
    }

    public function render()
    {
        $profil = Profil::first();
        return view('livewire.about-us', [
            'profil' => $profil,
        ]);
    }
}
