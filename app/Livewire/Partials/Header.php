<?php

namespace App\Livewire\Partials;

use App\Models\Profil;
use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        // Ambil logo perusahaan
        $companyLogo = Profil::first();

        return view('livewire.partials.header', [
            'companyLogo' => $companyLogo,
        ]);
    }
}
