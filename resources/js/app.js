import './bootstrap';

// Import Preline
import 'preline'

// Initialize Preline
document.addEventListener('DOMContentLoaded', () => {
    HSStaticMethods.autoInit();
});

// Re-initialize Preline after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    HSStaticMethods.autoInit();
});