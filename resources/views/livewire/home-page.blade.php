<div>
    <!-- Slider -->
    <div class="px-4 sm:px-6 lg:px-8 ">
        <div data-hs-carousel='{
          "loadingClasses": "opacity-0"
        }' class="relative">
            <div
                class="hs-carousel relative overflow-hidden w-full h-96 md:h-[calc(100vh-106px)] bg-gray-100 rounded-2xl dark:bg-neutral-800">
                <div
                    class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
                    <!-- Item -->
                    <div class="hs-carousel-slide">
                        <div
                            class="h-96 md:h-[calc(100vh-106px)] flex flex-col bg-[url('https://images.unsplash.com/photo-1615615228002-890bb61cac6e?q=80&w=1920&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-cover bg-center bg-no-repeat">
                            <div class="mt-auto w-2/3 md:max-w-lg ps-5 pb-5 md:ps-10 md:pb-10">
                                <span class="block text-white">Nike React</span>
                                <span class="block text-white text-xl md:text-3xl">Rewriting sport's playbook for
                                    billions of
                                    athletes</span>
                                <div class="mt-5">
                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl bg-white border border-transparent text-black hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
                                        href="#">
                                        Read Case Studies
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Item -->
                </div>
            </div>

            <!-- Arrows -->
            <button type="button"
                class="hs-carousel-prev hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-12 h-full text-black hover:bg-white/20 rounded-s-2xl focus:outline-hidden focus:bg-white/20">
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-3.5 md:size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                        </path>
                    </svg>
                </span>
                <span class="sr-only">Previous</span>
            </button>

            <button type="button"
                class="hs-carousel-next hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-12 h-full text-black hover:bg-white/20 rounded-e-2xl focus:outline-hidden focus:bg-white/20">
                <span class="sr-only">Next</span>
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-3.5 md:size-4" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                        </path>
                    </svg>
                </span>
            </button>
            <!-- End Arrows -->
        </div>
    </div>
    <!-- End Slider -->

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
