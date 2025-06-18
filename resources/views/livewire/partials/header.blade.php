<!-- ========== HEADER ========== -->
<header
    class="sticky top-4 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full before:absolute before:inset-0 before:max-w-5xl before:mx-2 lg:before:mx-auto before:bg-neutral-800/30 before:backdrop-blur-md">
    <nav
        class="relative max-w-5xl w-full py-2.5 ps-5 pe-2 md:flex md:items-center md:justify-between md:py-0 mx-2 lg:mx-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Logo -->
                <a wire:navigate href="/"
                    class="flex-none inline-block font-semibold focus:outline-hidden focus:opacity-80 logo-header"
                    aria-label="WebCompany">
                    @if ($companyLogo->logo)
                        <img src="{{ asset('storage/' . $companyLogo->logo) }}" alt="Logo"
                            class="h-12 md:h-12 max-w-[160px] w-auto object-contain rounded mr-4 transition-all duration-300" />
                    @else
                        <span class="text-white text-xl">WebCompany</span>
                    @endif
                </a>
                <!-- End Logo -->
            </div>

            <div class="md:hidden">
                <button type="button"
                    class="hs-collapse-toggle size-8 flex justify-center items-center text-sm font-semibold rounded-full bg-neutral-800 text-white disabled:opacity-50 disabled:pointer-events-none"
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

        <!-- Collapse -->
        <div id="hs-navbar-floating-dark"
            class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block"
            aria-labelledby="hs-navbar-floating-dark-collapse">
            <div class="flex flex-col md:flex-row md:items-center md:justify-end gap-y-3 py-2 md:py-0 md:ps-7">
                <a wire:navigate href="{{ route('Home') }}"
                    class="{{ request()->routeIs('Home') ? 'group inline-flex items-center gap-x-2 py-2 px-3 bg-[#ff0] font-medium text-sm text-neutral-800 rounded-full focus:outline-hidden' : 'pe-3 ps-px sm:px-3 md:py-4 text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300' }}">HOME</a>

                @foreach ($headerNavigations as $parentDropdown)
                    @php
                        $childDropdowns = $dropdownNavigations->where('parent_id', $parentDropdown->id);
                    @endphp
                    @if ($childDropdowns->count() > 0)
                        <div
                            class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--trigger:hover] [--adaptive:none] md:[--adaptive:adaptive] [--is-collapse:true] md:[--is-collapse:false] pe-3 ps-px sm:px-3 md:py-4">
                            @if ($parentDropdown->is_active_page && $parentDropdown->page_id)
                                <a wire:navigate href="{{ url('/' . $parentDropdown->slug) }}"
                                    class="hs-dropdown-toggle flex items-center w-full text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300"
                                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    {{ $parentDropdown->title }}
                                    <svg class="hs-dropdown-open:-rotate-180 md:hs-dropdown-open:rotate-0 duration-300 shrink-0 ms-auto md:ms-1 size-4"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </a>
                            @elseif ($parentDropdown->is_active_link && $parentDropdown->link)
                                <a href="{{ $parentDropdown->link }}" target="_blank"
                                    class="hs-dropdown-toggle flex items-center w-full text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300"
                                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    {{ $parentDropdown->title }}
                                    <svg class="hs-dropdown-open:-rotate-180 md:hs-dropdown-open:rotate-0 duration-300 shrink-0 ms-auto md:ms-1 size-4"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </a>
                            @else
                                <button id="hs-dropdown-floating-dark-{{ $parentDropdown->id }}" type="button"
                                    class="hs-dropdown-toggle flex items-center w-full text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300"
                                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    {{ $parentDropdown->title }}
                                    <svg class="hs-dropdown-open:-rotate-180 md:hs-dropdown-open:rotate-0 duration-300 shrink-0 ms-auto md:ms-1 size-4"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                            @endif
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 md:bg-neutral-800 md:shadow-md rounded-lg before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5"
                                role="menu" aria-orientation="vertical"
                                aria-labelledby="hs-dropdown-floating-dark-{{ $parentDropdown->id }}">
                                <div class="md:py-1 md:px-1 mt-3 md:mt-0 flex flex-col gap-y-3 md:gap-y-0">
                                    @foreach ($childDropdowns as $childDropdown)
                                        @if ($childDropdown->is_active_page && $childDropdown->PagesRelation)
                                            <a wire:navigate href="{{ url('/' . $childDropdown->slug) }}"
                                                class="flex items-center gap-x-3.5 md:py-2 md:px-3 rounded-lg text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300">
                                                {{ $childDropdown->title }}
                                            </a>
                                        @elseif($childDropdown->is_active_link && $childDropdown->link)
                                            <a href="{{ $childDropdown->link }}" target="_blank"
                                                class="flex items-center gap-x-3.5 md:py-2 md:px-3 rounded-lg text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300">
                                                {{ $childDropdown->title }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a wire:navigate href="{{ url('/' . $parentDropdown->slug) }}"
                            class="pe-3 ps-px sm:px-3 md:py-4 text-sm text-white hover:text-neutral-300 focus:outline-hidden focus:text-neutral-300 {{ request()->is($parentDropdown->slug) ? 'bg-[#ff0] font-medium text-neutral-800 rounded-full' : '' }}">
                            {{ $parentDropdown->title }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- End Collapse -->
    </nav>
</header>
<!-- ========== END HEADER ========== -->
