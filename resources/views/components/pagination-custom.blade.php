@php
    if (!isset($scrollTo)) {
        $scrollTo = true;
    }

    $scrollIntoViewJsSnippet = $scrollTo
        ? <<<JS
            var element = document.getElementById('pagination-section');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        JS
        : '';
@endphp

<style>
    select.no-arrow {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }

    select.no-arrow::-ms-expand {
        display: none;
    }
</style>

<div id="pagination-section">
    <div
        class="filament-tables-pagination-container p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Per page</label>
                    <select wire:model.live="perPage"
                        class="no-arrow h-10 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ trans_choice('Showing :from to :to of :total photos', $paginator->total(), [
                            'from' => $paginator->firstItem() ?: 0,
                            'to' => $paginator->lastItem() ?: 0,
                            'total' => $paginator->total(),
                        ]) }}
                    </p>
                </div>
            </div>
            @if ($paginator->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-end gap-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 rounded-lg cursor-not-allowed transition-colors duration-200">
                            <x-filament::icon alias="tables::pagination.buttons.previous" icon="heroicon-m-chevron-left"
                                class="w-5 h-5" />
                            <span class="ml-2 hidden sm:inline">Previous</span>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            wire:loading.class="opacity-70"
                            class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 transition-colors duration-200 disabled:opacity-70">
                            <span wire:loading.remove wire:target="previousPage">
                                <x-filament::icon alias="tables::pagination.buttons.previous"
                                    icon="heroicon-m-chevron-left" class="w-5 h-5" />
                            </span>
                            <span wire:loading wire:target="previousPage" class="w-5 h-5">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span class="ml-2 hidden sm:inline">Previous</span>
                        </button>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="hidden sm:flex items-center gap-1">
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span class="px-3 py-2 text-gray-500 dark:text-gray-400">
                                    {{ $element }}
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span
                                                class="inline-flex items-center justify-center w-10 h-10 bg-primary-500 text-white font-semibold rounded-lg">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <button type="button"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                                                wire:loading.class="opacity-70"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-all duration-200 disabled:opacity-70 relative">
                                                <span wire:loading.remove
                                                    wire:target="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                    class="z-10">
                                                    {{ $page }}
                                                </span>
                                                <span wire:loading
                                                    wire:target="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                    class="w-5 h-5">
                                                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach
                    </div>

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            wire:loading.class="opacity-70"
                            class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 transition-colors duration-200 disabled:opacity-70">
                            <span class="mr-2 hidden sm:inline">Next</span>
                            <span wire:loading.remove wire:target="nextPage">
                                <x-filament::icon alias="tables::pagination.buttons.next"
                                    icon="heroicon-m-chevron-right" class="w-5 h-5" />
                            </span>
                            <span wire:loading wire:target="nextPage" class="w-5 h-5">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    @else
                        <span
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 rounded-lg cursor-not-allowed transition-colors duration-200">
                            <span class="mr-2 hidden sm:inline">Next</span>
                            <x-filament::icon alias="tables::pagination.buttons.next" icon="heroicon-m-chevron-right"
                                class="w-5 h-5" />
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
</div>
