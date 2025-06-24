import './bootstrap';
import 'preline';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import { HSStaticMethods } from 'preline/preline';

// Initialize Preline
document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
document.addEventListener('livewire:navigated', () => HSStaticMethods.autoInit());

// Initialize Alpine and Livewire only once
if (!window.Livewire && !window.Alpine) {
    // Initialize Alpine first
    Alpine.start();

    // Then initialize Livewire with SPA mode
    Livewire.start({
        navigate: {
            enabled: true
        }
    });
}

document.addEventListener('DOMContentLoaded', handleDropdownTouch);
document.addEventListener('livewire:navigated', handleDropdownTouch);
document.addEventListener('livewire:load', handleDropdownTouch);