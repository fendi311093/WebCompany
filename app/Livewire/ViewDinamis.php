<?php

namespace App\Livewire;

use App\Models\Content;
use App\Models\NavigationWeb;
use App\Models\Page;
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

        // Coba cari di NavigationWeb dulu
        $this->button = NavigationWeb::where('slug', $slug)->first();

        if ($this->button) {
            // Jika ditemukan di NavigationWeb, gunakan cara lama
            $this->pages = $this->button->PagesRelation()->with('source')->first();
            $this->source = $this->pages?->source;
        } else {
            // Cari content berdasarkan slug
            $content = Content::where('slug', $slug)->firstOrFail();

            // Cari page yang terkait dengan content tersebut
            $this->pages = Page::where('source_type', 'App\Models\Content')
                ->where('source_id', $content->id)
                ->where('is_active', true)
                ->with('source')
                ->firstOrFail();

            $this->source = $this->pages->source;
        }

        // Dispatch title update event
        $this->dispatch('title-updated', strtoupper($this->button?->title ?? $this->source?->title ?? config('app.name')));
    }

    public function render()
    {
        $title = strtoupper($this->button?->title ?? $this->source?->title ?? config('app.name'));

        return view('livewire.view-dinamis', [
            'button' => $this->button,
            'pages' => $this->pages,
            'source' => $this->source,
            'title' => $title
        ]);
    }
}
