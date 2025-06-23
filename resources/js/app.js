import './bootstrap';
import Alpine from 'alpinejs';
import { HSStaticMethods } from 'preline/preline';

window.Alpine = Alpine;
Alpine.start();

// Initialize Preline
document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
document.addEventListener('livewire:navigated', () => HSStaticMethods.autoInit());

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

// Dark mode handler
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
} else {
    document.documentElement.classList.remove('dark')
}

// Whenever the user explicitly chooses light mode
window.setLightMode = function() {
    localStorage.theme = 'light'
    document.documentElement.classList.remove('dark')
}

// Whenever the user explicitly chooses dark mode
window.setDarkMode = function() {
    localStorage.theme = 'dark'
    document.documentElement.classList.add('dark')
}

// Whenever the user explicitly chooses to respect the OS preference
window.setSystemMode = function() {
    localStorage.removeItem('theme')
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
}