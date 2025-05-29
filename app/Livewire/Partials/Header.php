<?php

namespace App\Livewire\Partials;

use App\Models\HeaderButton;
use App\Models\Profil;
use Livewire\Component;

class Header extends Component
{
    public $companyLogo;
    public $headerButtons;

    public function mount()
    {
        $this->companyLogo = Profil::first();
        $this->headerButtons = HeaderButton::orderBy('position_header', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.partials.header', [
            'companyLogo' => $this->companyLogo,
            'headerButtons' => $this->headerButtons,
        ]);
    }
}
