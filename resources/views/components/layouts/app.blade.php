<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }"
    class="scroll-smooth">

<head>
    <meta charset="utf-8">

    {{-- Kode meta robots ini digunakan untuk mengontrol bagaimana mesin pencari menampilkan cuplikan halaman di hasil
    pencarian. --}}
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    {{-- width=device-width → Menyesuaikan lebar halaman dengan ukuran layar perangkat.
 	 initial-scale=1 → Menetapkan skala awal halaman agar tidak diperbesar atau diperkecil secara otomatis.
 	 shrink-to-fit=no → Mencegah browser mengecilkan elemen halaman agar sesuai dengan layar. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Dark mode script -->
    <script>
        // Check if theme is set in localStorage
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Make Alpine functions available globally
        window.setDarkMode = function() {
            localStorage.theme = 'dark';
            document.documentElement.classList.add('dark');
            window.Alpine && window.Alpine.store('darkMode').toggle(true);
        };

        window.setLightMode = function() {
            localStorage.theme = 'light';
            document.documentElement.classList.remove('dark');
            window.Alpine && window.Alpine.store('darkMode').toggle(false);
        };
    </script>

    <!-- Alpine.js Store for Dark Mode -->
    <script defer>
        document.addEventListener('alpine:init', () => {
            Alpine.store('darkMode', {
                on: localStorage.theme === 'dark',
                toggle(value) {
                    this.on = value;
                    value ? setDarkMode() : setLightMode();
                }
            })
        })
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
</head>

{{-- Mengatur layout flex untuk memastikan footer tetap berada di bagian bawah halaman --}}

<body class="bg-white dark:bg-neutral-900 min-h-screen flex flex-col" x-cloak>
    @livewire('partials.header')
    <Main id="content" class="flex-1">
        {{ $slot }}
    </Main>
    @livewire('partials.footer')
    @filamentScripts
    @livewireScripts
</body>

</html>
