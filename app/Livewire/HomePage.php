<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Web Company')]
class HomePage extends Component
{
    public function render()
    {
        $customers = Customer::all();

        // Ambil data slider yang aktif, urutkan berdasarkan slide_number
        $sliders = Slider::where('is_active', true)
            ->orderBy('slide_number', 'asc')
            ->with('photo') // Load relasi photo
            ->get();

        // Debug info
        if ($sliders->count() > 0) {
            foreach ($sliders as $slider) {
                // Log info untuk debugging
                Log::info('Slider #' . $slider->slide_number . ' loaded', [
                    'photo_id' => $slider->photo_id,
                    'photo_exists' => $slider->photo ? 'Yes' : 'No',
                    'file_path' => $slider->photo ? $slider->photo->file_path : 'N/A',
                    'full_path' => $slider->photo ? public_path('storage/' . $slider->photo->file_path) : 'N/A',
                    'file_exists' => $slider->photo ? file_exists(public_path('storage/' . $slider->photo->file_path)) : false
                ]);
            }
        } else {
            Log::info('No active sliders found');
        }

        return view('livewire.home-page', [
            'customers' => $customers,
            'sliders' => $sliders
        ]);
    }
}
