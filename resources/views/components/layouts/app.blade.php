@props(['title' => config('app.name')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">

    {{-- Kode meta robots ini digunakan untuk mengontrol bagaimana mesin pencari menampilkan cuplikan halaman di hasil
    pencarian. --}}
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    {{-- width=device-width → Menyesuaikan lebar halaman dengan ukuran layar perangkat.
 	 initial-scale=1 → Menetapkan skala awal halaman agar tidak diperbesar atau diperkecil secara otomatis.
 	 shrink-to-fit=no → Mencegah browser mengecilkan elemen halaman agar sesuai dengan layar. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('DKD_LOGO_ONLY.png') }}">

    <title x-data="{ title: '{{ $title }}' }" x-text="title" x-on:title-updated.window="title = $event.detail"></title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
</head>

{{-- Mengatur layout flex untuk memastikan footer tetap berada di bagian bawah halaman --}}

<body x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => {
    localStorage.setItem('theme', val ? 'dark' : 'light');
    if (val) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});
if (darkMode) document.documentElement.classList.add('dark');" :class="{ 'dark': darkMode }"
    class="bg-white dark:bg-neutral-900 min-h-screen flex flex-col" x-cloak>

    @livewire('partials.header')
    <Main id="content" class="flex-1">
        {{ $slot }}
    </Main>
    @livewire('partials.footer')

    @filamentScripts
    @livewireScripts

    <script>
        // Handle SPA navigation
        document.addEventListener('livewire:init', () => {
            Livewire.on('page-load', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
