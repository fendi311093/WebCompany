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

// Cegah navigasi langsung pada parent dropdown di mobile/tablet
function handleDropdownTouch() {
    const parents = document.querySelectorAll('button.header-dropdown-parent');
    parents.forEach(function(el) {
        el.addEventListener('touchend', function(e) {
            if (window.innerWidth < 768) {
                if (!el.dataset.tapped) {
                    e.preventDefault();
                    el.dataset.tapped = 'true';
                    el.click(); // buka dropdown
                    setTimeout(() => { el.removeAttribute('data-tapped'); }, 1500);
                } else {
                    e.preventDefault();
                    const url = el.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                    el.removeAttribute('data-tapped');
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', handleDropdownTouch);
document.addEventListener('livewire:navigated', handleDropdownTouch);
document.addEventListener('livewire:load', handleDropdownTouch);