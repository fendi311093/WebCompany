<?php

namespace App\Livewire;

use App\Models\HeaderButton;
use App\Models\NavigationWeb;
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
        $this->button = NavigationWeb::where('slug', $slug)->firstOrFail();
        $this->pages = $this->button->PagesRelation()->with('source')->first();
        $this->source = $this->pages?->source;

        // Dispatch title update event
        $this->dispatch('title-updated', strtoupper($this->button->title ?? config('app.name')));
    }

    public function render()
    {
        $title = strtoupper($this->button->title ?? config('app.name'));

        return view('livewire.view-dinamis', [
            'button' => $this->button,
            'pages' => $this->pages,
            'source' => $this->source,
            'title' => $title
        ]);
    }
}
