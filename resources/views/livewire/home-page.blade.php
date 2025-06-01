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
                class="hs-carousel relative overflow-hidden w-full h-96 md:h-[calc(100vh-106px)] bg-gray-100 rounded-2xl dark:bg-neutral-800">
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
                                            <!-- Slight scale to avoid white edges -->
                                            <img src="{{ asset('storage/' . $slider->photo->file_path) }}"
                                                alt="Slider Image #{{ $slider->slide_number }}"
                                                class="w-full h-full object-cover object-center"
                                                style="object-position: center center; max-width: none; max-height: none;"
                                                onerror="this.onerror=null; this.classList.add('bg-gray-800', 'p-4'); this.src=''; this.alt='Image not found';">
                                        </div>

                                        <!-- Gradient overlay for better text visibility -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-70">
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="mt-auto relative z-10 w-full md:w-2/3 p-6 md:p-10">
                                        <span
                                            class="inline-block mb-1 px-3 py-1 bg-primary-500 text-white text-2xl font-semibold rounded-full">
                                            24/7 Service
                                        </span>
                                        <p class="text-white text-xl md:text-4xl font-bold mb-4">
                                            We are open for 24 hours from Monday to Sunday.
                                        </p>
                                        <a href="#"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-white text-black hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none py-3 px-4">
                                            Lihat Detail
                                            <svg class="w-2 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- End Slider Item -->
                        @endforeach
                    @else
                        <!-- Default Slider jika tidak ada data -->
                        <div class="hs-carousel-slide">
                            <div class="h-96 md:h-[calc(100vh-106px)] flex flex-col bg-gray-300 dark:bg-gray-700">
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
    <div class="bg-neutral-900">
        <div class="max-w-screen-2xl px-4 xl:px-0 py-3 mx-auto">
            <div class="container mx-auto">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-semibold text-white">Our Customers</h2>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
                    @if ($customers->count() > 0)
                        @foreach ($customers as $customer)
                            <img src="{{ url('storage/' . $customer->logo) }}" alt="{{ $customer->name_customer }}"
                                class="h-10 md:h-14 w-auto mx-4 object-contain transition duration-300" />
                        @endforeach
                    @else
                        <div class="text-red-500">Tidak ada data customer!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Clients -->

    <!-- Main Content -->
    <div class="bg-neutral-900">
        <div class="max-w-screen-2xl px-4 xl:px-0 py-6 mx-auto">
            <div class="border border-neutral-800 rounded-xl">
                <div class="p-4 lg:p-8 bg-gradient-to-bl from-neutral-800 via-neutral-900 to-neutral-950 rounded-xl">
                    <!-- Grid -->
                    <div class="flex flex-wrap justify-center items-stretch gap-8">
                        @if ($contents->count() > 0)
                            @foreach ($contents as $content)
                                <!-- Card -->
                                <a class="group flex flex-col h-full focus:outline-hidden w-full sm:w-[calc(50%-2rem)] lg:w-[calc(33.333%-2rem)]"
                                    href="#">
                                    <div class="relative w-full h-64 rounded-xl overflow-hidden bg-neutral-800">
                                        <img class="w-full h-full object-cover object-center bg-neutral-800 rounded-xl group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out"
                                            src="{{ url('storage/' . $content->photo) }}" alt="{{ $content->title }}"
                                            loading="lazy">
                                    </div>
                                    <div class="mt-4 flex flex-col h-full">
                                        <h3
                                            class="text-xl font-semibold text-gray-400 group-hover:text-gray-300 transition-colors line-clamp-2">
                                            {{ $content->title }}
                                        </h3>
                                        <p class="mt-3 text-gray-200 line-clamp-3">
                                            {{ $content->description }}
                                        </p>
                                        <p
                                            class="mt-5 inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 group-hover:underline group-focus:underline font-medium dark:text-blue-500">
                                            Read more
                                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="m9 18 6-6-6-6" />
                                            </svg>
                                        </p>
                                    </div>
                                </a>
                                <!-- End Card -->
                            @endforeach
                        @else
                            <div class="text-red-500">No content data!</div>
                        @endif
                    </div>
                    <!-- End Grid -->

                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
</div>
