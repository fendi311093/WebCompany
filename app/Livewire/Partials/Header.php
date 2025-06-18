<?php

namespace App\Livewire\Partials;

use App\Models\NavigationWeb;
use App\Models\Profil;
use Livewire\Component;

class Header extends Component
{
    public $companyLogo;
    public $headerNavigations;
    public $dropdownNavigations;

    public function mount()
    {
        $this->companyLogo = Profil::first();

        // Ambil data navigation web untuk type header
        $this->headerNavigations = NavigationWeb::where('type', 'header')
            ->orderBy('position', 'asc')
            ->with('PagesRelation')
            ->get();

        // Ambil data navigation web untuk type dropdown
        $this->dropdownNavigations = NavigationWeb::where('type', 'dropdown')
            ->orderBy('position', 'asc')
            ->with(['PagesRelation', 'parentNavigation'])
            ->get();
    }

    public function render()
    {
        return view('livewire.partials.header', [
            'companyLogo' => $this->companyLogo,
            'headerNavigations' => $this->headerNavigations,
            'dropdownNavigations' => $this->dropdownNavigations,
        ]);
    }
}
