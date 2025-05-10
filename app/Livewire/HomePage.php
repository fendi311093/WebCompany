<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Web Company')]
class HomePage extends Component
{
    public function render()
    {
        $customers = Customer::all();
        // Debug
        // dd($customers);
        return view('livewire.home-page', [
            'customers'  => $customers
        ]);
    }
}
