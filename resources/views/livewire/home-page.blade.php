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
                                            class="inline-block mb-2 px-3 py-1 bg-primary-500 text-white text-2xl font-semibold rounded-full">
                                            24/7 Service
                                        </span>
                                        <h2 class="text-white text-2xl md:text-4xl font-bold mb-4">
                                            We are open for 24 hours from Monday to Sunday.
                                        </h2>
                                        <a href="#"
                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-white text-black hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none py-3 px-4">
                                            Lihat Detail
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                                    <span class="block text-white">Tidak ada slider</span>
                                    <span class="block text-white text-xl md:text-3xl">
                                        Silahkan tambahkan slider melalui admin panel
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

    <!-- Debug Section jika slider kosong atau tidak ada foto -->
    @if ($sliders->count() == 0 || $sliders->whereNull('photo.file_path')->count() > 0)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4 mx-4" role="alert">
            <strong class="font-bold">Debug Info:</strong>
            <div class="mt-2">
                <p>Jumlah Slider Aktif: {{ $sliders->count() }}</p>
                @foreach ($sliders as $slider)
                    <div class="mt-1 p-2 border-t border-red-300">
                        <p>Slider #{{ $slider->slide_number }}:
                            Photo ID: {{ $slider->photo_id ?? 'NULL' }},
                            Photo Path: {{ $slider->photo->file_path ?? 'NULL' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Clients -->
    <div class="bg-gray-500 shadow px-4 sm:px-6 lg:px-8 my-8">
        <div
            class="relative py-6 md:py-10 overflow-hidden dark:border-neutral-700 before:absolute before:top-0 before:start-0 before:z-10 before:w-20 before:h-full before:bg-linear-to-r before:from-white before:to-transparent after:absolute after:top-0 after:end-0 after:w-20 after:h-full after:bg-linear-to-l after:from-white after:to-transparent dark:before:from-neutral-900 dark:after:from-neutral-900">
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-white">Our Customers</h2>
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
    <!-- End Clients -->
</div>
