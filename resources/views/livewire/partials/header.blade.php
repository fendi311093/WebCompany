<!-- ========== HEADER ========== -->
<header
    class="sticky top-2 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full before:absolute before:inset-0 before:max-w-5xl before:mx-2 lg:before:mx-auto before:rounded-[20px] before:bg-neutral-800/30 before:backdrop-blur-md">
    <nav
        class="relative max-w-5xl w-full flex flex-wrap md:flex-nowrap basis-full items-center justify-between py-2 md:py-3 ps-4 pe-2 mx-2 lg:mx-auto">
        <div class="flex items-center">
            <!-- Logo -->
            <a class="flex-none rounded-md text-lg inline-block font-semibold focus:outline-hidden focus:opacity-80 transition-transform duration-300 ease-in-out hover:scale-110"
                href="/" aria-label="{{ $companyLogo->name_company ?? 'Logo' }}">
                @if ($companyLogo && $companyLogo->logo)
                    <img src="{{ asset('storage/' . $companyLogo->logo) }}" alt="{{ $companyLogo->name_company }}"
                        class="h-12 md:h-14 w-auto rounded-xl transition-all duration-300">
                @else
                    <span
                        class="text-white text-base md:text-xl font-bold rounded-xl">{{ $companyLogo->name_company ?? 'LOGO' }}</span>
                @endif
            </a>
            <!-- End Logo -->
        </div>

        <!-- Button Group -->
        <div class="md:order-3 flex items-center gap-x-3">
            {{-- <div class="md:ps-3">
                <a class="group inline-flex items-center gap-x-2 py-2 px-3 bg-[#ff0] font-medium text-sm text-nowrap text-neutral-800 rounded-full focus:outline-hidden"
                    href="#">
                    Request demo
                </a>
            </div> --}}

            <div class="md:hidden">
                <button type="button"
                    class="hs-collapse-toggle size-9 flex justify-center items-center text-sm font-semibold rounded-full bg-neutral-800 text-white disabled:opacity-50 disabled:pointer-events-none"
                    id="hs-navbar-floating-dark-collapse" aria-expanded="false" aria-controls="hs-navbar-floating-dark"
                    aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-floating-dark">
                    <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                    <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- End Button Group -->

        <!-- Collapse -->
        <div id="hs-navbar-floating-dark"
            class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block"
            aria-labelledby="hs-navbar-floating-dark-collapse">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-y-3 gap-x-2 py-2 md:py-0 md:ps-7">
                <!-- Home Button -->
                <a wire:navigate href="{{ route('Home') }}"
                    class="{{ request()->routeIs('Home') ? 'group inline-flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base bg-[#1df59b] font-medium text-neutral-900 rounded-full focus:outline-hidden' : 'inline-flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base font-medium text-neutral-900 dark:text-white hover:bg-[#1df59b] transition-colors duration-200 rounded-full focus:outline-hidden' }}">
                    HOME
                </a>

                <!-- Dynamic Navigation -->
                @foreach ($headerNavigations->sortBy('position') as $nav)
                    @if ($nav->is_active_page)
                        <a wire:navigate
                            href="{{ $nav->is_active_link ? $nav->link : ($nav->page_id ? '/' . $nav->slug : '#') }}"
                            class="{{ request()->path() === $nav->slug ? 'group inline-flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base bg-[#1df59b] font-medium text-neutral-800 rounded-full focus:outline-hidden' : 'inline-flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base font-medium text-neutral-900 dark:text-white hover:bg-[#1df59b] transition-colors duration-200 rounded-full focus:outline-hidden' }}">
                            {{ $nav->title }}
                        </a>
                    @else
                        <!-- Header with Dropdown -->
                        @if ($dropdownNavigations->where('parent_id', $nav->id)->count() > 0)
                            <div
                                class="hs-dropdown [--strategy:static] sm:[--strategy:fixed] [--adaptive:none] sm:[--trigger:hover] sm:py-2">
                                <button type="button"
                                    class="hs-dropdown-toggle flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base text-white hover:bg-[#1df59b] hover:text-neutral-800 transition-colors duration-200 rounded-full focus:outline-hidden">
                                    {{ $nav->title }}
                                    <svg class="hs-dropdown-open:rotate-180 size-3" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div
                                    class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[15rem] bg-[#1df59b] shadow-md rounded-lg p-2 mt-2 before:absolute before:-top-5 before:left-0 before:w-full before:h-5">
                                    @foreach ($dropdownNavigations->where('parent_id', $nav->id)->sortBy('position') as $childNav)
                                        <a wire:navigate
                                            href="{{ $childNav->is_active_link ? $childNav->link : ($childNav->page_id ? '/' . $childNav->slug : '#') }}"
                                            class="flex items-center gap-x-3.5 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base text-neutral-800 hover:bg-[#19cc83] rounded-lg font-medium">
                                            {{ $childNav->title }}
                                            <svg class="size-3 ml-auto" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="m9 18 6-6-6-6" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Regular Header Button -->
                            <button type="button"
                                class="inline-flex items-center gap-x-2 py-1.5 px-2 md:py-2 md:px-3 text-sm md:text-base text-white hover:bg-[#1df59b] hover:text-neutral-800 transition-colors duration-200 rounded-full focus:outline-hidden">
                                {{ $nav->title }}
                            </button>
                        @endif
                    @endif
                @endforeach

                <!-- Theme Switcher -->
                <div x-data class="flex items-center gap-2">
                    <button type="button" @click="$store.darkMode.toggle(!$store.darkMode.on)"
                        class="p-2 text-white hover:text-[#1df59b] transition-colors duration-200"
                        :aria-label="$store.darkMode.on ? 'Switch to light mode' : 'Switch to dark mode'">
                        <!-- Sun icon -->
                        <svg x-show="$store.darkMode.on" class="size-5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon -->
                        <svg x-show="!$store.darkMode.on" class="size-5" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
        <!-- End Collapse -->
    </nav>
</header>
<!-- ========== END HEADER ========== -->
