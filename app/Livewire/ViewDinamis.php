<?php

namespace App\Livewire;

use App\Models\HeaderButton;
use Livewire\Component;

class ViewDinamis extends Component
{
    public $slug;
    public $button;
    public $pages;
    public $source;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->button = HeaderButton::where('slug', $slug)->firstOrFail();
        $this->pages = $this->button->Pages()->with('source')->first();
        $this->source = $this->pages?->source;
    }

    public function render()
    {
        return view('livewire.view-dinamis', [
            'button' => $this->button,
            'pages' => $this->pages,
            'source' => $this->source,
        ]);
    }
}
