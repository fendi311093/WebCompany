<div>
    <!-- Slider -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div data-hs-carousel='{
          "loadingClasses": "opacity-0",
          "interval": 5000,
          "isAutoPlay": true,
          "isInfinite": true
        }'
            class="relative">
            <div
                class="hs-carousel relative overflow-hidden w-full h-96 md:h-[calc(100vh-106px)] bg-white dark:bg-neutral-800 rounded-2xl shadow-xl dark:shadow-neutral-800/50 border border-gray-100 dark:border-neutral-700">
                <div
                    class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">

                    @if ($sliders->count() > 0)
                        @foreach ($sliders as $slider)
                            <!-- Slider Item -->
                            <div class="hs-carousel-slide">
                                <div class="relative h-96 md:h-[calc(100vh-106px)] flex flex-col overflow-hidden">
                                    <!-- Background Image -->
                                    <div class="absolute inset-0 w-full h-full overflow-hidden">
                                        <div class="w-full h-full transform scale-[1.02]">
                                            <img src="{{ asset('storage/' . $slider->photo->file_path) }}"
                                                alt="Slider Image #{{ $slider->slide_number }}"
                                                class="w-full h-full object-cover object-center"
                                                style="object-position: center center; max-width: none; max-height: none;"
                                                onerror="this.onerror=null; this.classList.add('bg-gray-100', 'dark:bg-gray-800', 'p-4'); this.src=''; this.alt='Image not found';">
                                        </div>

                                        <!-- Gradient overlay for better text visibility -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-60 dark:opacity-50">
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    {{-- <div class="mt-auto relative z-10 w-full md:w-2/3 p-6 md:p-10">
                                        <span
                                            class="inline-block mb-1 px-3 py-1 bg-[#1df59b] text-neutral-800 text-2xl font-semibold rounded-full">
                                            24/7 Service
                                        </span>
                                        <p class="text-white text-xl md:text-4xl font-bold mb-4">
                                            We are open for 24 hours from Monday to Sunday.
                                        </p>
                                        <a href="#"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-white text-neutral-800 hover:bg-gray-200 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700 disabled:opacity-50 disabled:pointer-events-none py-3 px-4 transition-colors duration-200">
                                            Lihat Detail
                                            <svg class="w-2 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>
                                    </div> --}}
                                </div>
                            </div>
                            <!-- End Slider Item -->
                        @endforeach
                    @else
                        <!-- Default Slider jika tidak ada data -->
                        <div class="hs-carousel-slide">
                            <div class="h-96 md:h-[calc(100vh-106px)] flex flex-col bg-gray-100 dark:bg-gray-800">
                                <div class="mt-auto w-2/3 md:max-w-lg ps-5 pb-5 md:ps-10 md:pb-10">
                                    <span class="block text-red-500">No Sliders</span>
                                    <span class="block text-red-500 text-xl md:text-3xl">
                                        Please add a slider
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Arrows -->
            <button type="button"
                class="hs-carousel-prev hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-12 h-full text-white hover:bg-black/30 rounded-s-2xl focus:outline-hidden focus:bg-black/30 z-10">
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-5 md:size-6" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                        </path>
                    </svg>
                </span>
                <span class="sr-only">Previous</span>
            </button>

            <button type="button"
                class="hs-carousel-next hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-12 h-full text-white hover:bg-black/30 rounded-e-2xl focus:outline-hidden focus:bg-black/30 z-10">
                <span class="sr-only">Next</span>
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-5 md:size-6" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                        </path>
                    </svg>
                </span>
            </button>
            <!-- End Arrows -->

            <!-- Pagination Indicators -->
            <div class="flex justify-center absolute bottom-3 start-0 end-0 z-10">
                <div class="flex items-center gap-x-1 hs-carousel-pagination">
                    @if ($sliders->count() > 0)
                        @foreach ($sliders as $index => $slider)
                            <span
                                class="size-2.5 bg-white/[.8] rounded-full cursor-pointer {{ $index === 0 ? 'bg-white' : '' }}"
                                data-hs-carousel-pagination-item="{{ $index }}"></span>
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- End Pagination Indicators -->
        </div>
    </div>
    <!-- End Slider -->

    <!-- Clients -->
    <div class="bg-white dark:bg-neutral-900">
        <div class="px-4 py-8">
            <div class="max-w-screen-xl mx-auto">
                <div class="text-center">
                    <h2
                        class="text-3xl font-bold text-gray-900 dark:text-white relative inline-block after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-[#1df59b] after:rounded-full">
                        Our Customers
                    </h2>
                    <p class="mt-3 text-gray-600 dark:text-gray-300">Trusted by leading companies worldwide</p>
                </div>
                <div class="max-w-[1920px] mx-auto px-4">
                    @php
                        $activeCustomers = $customers->where('is_active', true);
                        $count = $activeCustomers->count();
                        // Maksimal 6 kolom agar tetap responsif
                        $cols = min($count, 6);
                    @endphp

                    @if ($activeCustomers->count() > 0)
                        <div class="flex flex-wrap justify-center items-center gap-8 max-w-7xl mx-auto">
                            @foreach ($activeCustomers as $index => $customer)
                                <div
                                    class="group transition-all duration-300 hover:scale-110 
                                    w-[calc(50%-1rem)] 
                                    sm:w-[calc(33.333%-1.5rem)] 
                                    md:w-[calc(25%-1.5rem)] 
                                    lg:w-[calc(20%-1.6rem)] 
                                    xl:w-[calc(16.666%-1.7rem)] 
                                    2xl:w-[calc(12.5%-1.75rem)] 
                                    flex items-center justify-center h-24 min-w-[150px] max-w-[200px]">
                                    <img src="{{ url('storage/' . $customer->logo) }}"
                                        alt="{{ $customer->name_customer }}"
                                        class="max-h-16 w-auto object-contain transition duration-300 drop-shadow-2xl dark:brightness-110 light:contrast-125 light:brightness-90 mix-blend-multiply dark:mix-blend-normal" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-red-500 py-8 text-center">Tidak ada data customer!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Clients -->

    <!-- Main Content -->
    <div class="bg-white dark:bg-neutral-900">
        <div class="px-4 py-1">
            <div
                class="border border-gray-200/80 dark:border-neutral-700/50 rounded-2xl shadow-lg dark:shadow-neutral-800/30 backdrop-blur-sm">
                <div class="p-4 lg:p-8 bg-white dark:bg-neutral-800 rounded-2xl">
                    <!-- Grid -->
                    <div class="flex flex-wrap justify-center items-stretch gap-8">
                        @php
                            $pages = \App\Models\Page::where('source_type', 'App\Models\Content')
                                ->where('is_active', true)
                                ->get();
                        @endphp

                        @if ($pages->count() > 0)
                            @foreach ($pages as $page)
                                <!-- Card -->
                                <a wire:navigate
                                    class="group flex flex-col h-full focus:outline-hidden w-full sm:w-[calc(50%-2rem)] lg:w-[calc(33.333%-2rem)]"
                                    href="{{ route('ViewDinamis', ['slug' => $page->source->slug]) }}">
                                    <div
                                        class="relative w-full h-64 rounded-xl overflow-hidden shadow-md dark:shadow-neutral-800/50 bg-white dark:bg-neutral-800">
                                        <img class="w-full h-full object-cover object-center bg-white dark:bg-neutral-800 rounded-xl group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out"
                                            src="{{ url('storage/' . $page->source->photo) }}"
                                            alt="{{ $page->source->title }}" loading="lazy">
                                    </div>
                                    <div
                                        class="mt-4 p-4 rounded-xl bg-white dark:bg-neutral-800 backdrop-blur-sm border border-gray-100 dark:border-neutral-700/50 shadow-sm">
                                        <h3
                                            class="text-xl font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                            {{ $page->source->title }}
                                        </h3>
                                        <p class="mt-3 text-gray-600 dark:text-gray-300 line-clamp-3">
                                            {{ $page->source->description }}
                                        </p>
                                        <p
                                            class="mt-5 inline-flex items-center gap-x-1 text-sm font-medium text-primary-600 decoration-2 group-hover:underline dark:text-primary-400">
                                            Read more
                                            <svg class="shrink-0 size-4 transition-transform group-hover:translate-x-1"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m9 18 6-6-6-6" />
                                            </svg>
                                        </p>
                                    </div>
                                </a>
                                <!-- End Card -->
                            @endforeach
                        @else
                            <div class="text-red-500">Tidak ada halaman konten yang aktif!</div>
                        @endif
                    </div>
                    <!-- End Grid -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
</div>
