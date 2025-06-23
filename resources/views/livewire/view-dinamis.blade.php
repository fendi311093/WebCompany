<!-- Features -->
<div class="bg-white dark:bg-neutral-900">
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Grid -->
        <div class="md:grid md:grid-cols-2 md:items-center md:gap-12 xl:gap-32">
            @if ($pages && $source)
                <div>
                    @if (isset($source->photo))
                        <img class="rounded-xl shadow-lg dark:shadow-neutral-800/50"
                            src="{{ asset('storage/' . $source->photo) }}"
                            alt="{{ $source->name_company ?? ($source->name_customer ?? $source->title) }}">
                    @endif
                </div>
                <!-- End Col -->

                <div class="mt-5 sm:mt-10 lg:mt-0">
                    <div class="space-y-6 sm:space-y-8">
                        <!-- Title -->
                        <div class="space-y-2 md:space-y-4">
                            <h2
                                class="font-bold text-3xl lg:text-4xl text-neutral-800 dark:text-white relative inline-block">
                                {{ $source->name_company ?? ($source->name_customer ?? ($source->title ?? '-')) }}
                                <span class="absolute bottom-0 left-0 w-full h-1 bg-[#1df59b] transform"></span>
                            </h2>
                            <div class="prose dark:prose-invert text-neutral-600 dark:text-gray-200 max-w-none">
                                {!! isset($source->description)
                                    ? str($source->description)->markdown()->sanitizeHtml()
                                    : (isset($pages->content)
                                        ? str($pages->content)->markdown()->sanitizeHtml()
                                        : '') !!}
                            </div>
                        </div>
                        <!-- End Title -->
                    </div>
                </div>
                <!-- End Col -->
            @endif
        </div>
        <!-- End Grid -->
    </div>
</div>
<!-- End Features -->
