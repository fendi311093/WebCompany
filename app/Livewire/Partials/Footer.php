<?php

namespace App\Livewire\Partials;

use App\Models\Profil;
use Livewire\Component;

class Footer extends Component
{
    public $profilCompany;

    public function mount()
    {
        $this->profilCompany = Profil::select('logo', 'name_company')->first();
    }

    public function render()
    {
        return view('livewire.partials.footer', [
            'profilCompany' => $this->profilCompany
        ]);
    }
}
