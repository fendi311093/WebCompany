<x-filament-panels::page>
    @php
        $photos = $this->getPhotos();
    @endphp
    <div class="space-y-6">
        <!-- Gallery Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        List Photos
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        {{ $photos->total() }} photos in your collection
                    </p>
                </div>
                <div x-data="{ loading: false }" class="flex items-center space-x-2">
                    <a wire:navigate href="{{ route('filament.admin.resources.photos.create') }}" @click="loading = true"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg x-show="loading" class="animate-spin w-5 h-5 mr-2 text-white" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span x-show="!loading" class="mr-2">
                            <x-filament::icon icon="heroicon-o-plus" class="w-5 h-5" />
                        </span>
                        Create
                    </a>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        @if ($photos->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-8">
                @foreach ($photos as $photo)
                    <div x-data="{ showActions: false }" @mouseenter="showActions = true" @mouseleave="showActions = false"
                        @click.away="showActions = false" @touchstart="showActions = true"
                        class="group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col">
                        <!-- Photo Container -->
                        <div
                            class="relative flex-1 aspect-[4/3] flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                            <img src="{{ Storage::disk('public')->url($photo->file_path) }}" alt="Photo"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy" />

                            <!-- Overlay with actions -->
                            <div class="absolute inset-0 bg-black/50 transition-all duration-300" x-show="showActions"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="flex items-center space-x-4">
                                        <!-- Edit Button -->
                                        <a wire:navigate
                                            href="{{ route('filament.admin.resources.photos.edit', $photo->getHashedId()) }}"
                                            class="inline-flex items-center justify-center w-14 h-14 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-full transition-colors duration-200 shadow-lg transform hover:scale-105 active:scale-95"
                                            title="Edit Photo">
                                            <x-filament::icon icon="heroicon-o-pencil" class="w-7 h-7" />
                                        </a>

                                        <!-- Delete Button & Modal -->
                                        <div x-data="{ open: false }">
                                            <button type="button" @click="open = true"
                                                class="inline-flex items-center justify-center w-14 h-14 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white rounded-full transition-colors duration-200 shadow-lg transform hover:scale-105 active:scale-95"
                                                title="Delete Photo">
                                                <x-filament::icon icon="heroicon-o-trash" class="w-7 h-7" />
                                            </button>

                                            <!-- Modal Konfirmasi -->
                                            <div x-show="open" x-cloak @click.away="open = false"
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                                                x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-2xl w-full max-w-sm mx-4 sm:mx-auto"
                                                    @click.stop x-transition:enter="ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                    x-transition:leave="ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                                    <h3
                                                        class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                                                        Konfirmasi Hapus
                                                    </h3>
                                                    <p class="mb-4 text-gray-600 dark:text-gray-300">
                                                        Apakah Anda yakin ingin menghapus foto ini?
                                                    </p>
                                                    <div class="flex justify-end gap-3">
                                                        <button type="button" @click="open = false"
                                                            class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                            Batal
                                                        </button>
                                                        <button type="button"
                                                            @click="$wire.deletePhoto({{ $photo->id }}); open = false"
                                                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                            Ya, Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photo info -->
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ basename($photo->file_path) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $photo->updated_at->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Photo
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $photos->links('components.pagination-custom', ['scrollTo' => false]) }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12">
                <div class="text-center">
                    <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
                        <x-filament::icon icon="heroicon-o-camera" class="w-full h-full" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        No photos yet
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                        Start building your photo collection by uploading your first photo.
                    </p>
                    <div class="flex items-center justify-center space-x-3">
                        <x-filament::button icon="heroicon-o-plus" tag="a"
                            href="{{ route('filament.admin.resources.photos.create') }}">
                            Add First Photo
                        </x-filament::button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
