<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    {{-- Kode meta robots ini digunakan untuk mengontrol bagaimana mesin pencari menampilkan cuplikan halaman di hasil
    pencarian. --}}
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    {{-- width=device-width → Menyesuaikan lebar halaman dengan ukuran layar perangkat.
 	 initial-scale=1 → Menetapkan skala awal halaman agar tidak diperbesar atau diperkecil secara otomatis.
 	 shrink-to-fit=no → Mencegah browser mengecilkan elemen halaman agar sesuai dengan layar. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ $title ?? 'Web Company' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

{{-- Mengatur layout flex untuk memastikan footer tetap berada di bagian bawah halaman --}}

<body class="bg-neutral-900 flex flex-col min-h-screen">
    @livewire('partials.header')
    <Main id="content" class="flex-1">
        {{ $slot }}
    </Main>
    @livewire('partials.footer')
    @livewireScripts

    {{-- Kode ini diperlukan agar elemen UI yang bergantung pada JavaScript tetap berfungsi setelah halaman berubah tanpa
    harus refresh secara manual. --}}
    <script>
        document.addEventListener("livewire:navigated", function() {
            if (window.HSStaticMethods && window.HSStaticMethods.autoInit) {
                window.HSStaticMethods.autoInit();
            }
        });
    </script>
</body>

</html>
