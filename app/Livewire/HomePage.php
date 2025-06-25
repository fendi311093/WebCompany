<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Page;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Web Company')]
class HomePage extends Component
{
    public $customers;
    public $activeCustomers;
    public $customerCols;
    public $sliders;
    public $pages;

    public function mount()
    {
        // Get all customers
        $this->customers = Customer::all();

        // Filter active customers and calculate columns
        $this->activeCustomers = $this->customers->where('is_active', true);
        $count = $this->activeCustomers->count();
        $this->customerCols = min($count, 6);

        // Get active sliders with photos
        $this->sliders = Slider::where('is_active', true)
            ->orderBy('slide_number', 'asc')
            ->with('photo')
            ->get();

        // Get active content pages with their sources
        $this->pages = Page::where('source_type', 'App\Models\Content')
            ->where('is_active', true)
            ->with('source')
            ->get();
    }

    public function render()
    {
        return view('livewire.home-page', [
            'customers' => $this->customers,
            'activeCustomers' => $this->activeCustomers,
            'customerCols' => $this->customerCols,
            'sliders' => $this->sliders,
            'pages' => $this->pages,
        ]);
    }
}
