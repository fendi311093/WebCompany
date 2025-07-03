<x-filament-panels::page>
    @php
        $photos = $this->getPhotos();
    @endphp

    <!-- Global Delete Confirmation Modal -->
    <x-filament::modal id="confirm-delete-modal" width="sm" alignment="center">
        <x-slot name="trigger"></x-slot>

        <x-slot name="heading">
            Confirm Delete
        </x-slot>

        <x-slot name="description">
            Are you sure you want to delete this photo?
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end gap-x-3">
                <x-filament::button color="gray"
                    @click="$dispatch('close-modal', { id: 'confirm-delete-modal' }); $dispatch('notification-cleared')">
                    Cancel
                </x-filament::button>

                <x-filament::button color="danger" wire:click="deletePhoto({{ $photoToDelete }})"
                    @click="$dispatch('close-modal', { id: 'confirm-delete-modal' })" wire:loading.attr="disabled">
                    Yes, Delete
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>

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
                    <a wire:navigate href="{{ route('filament.admin.resources.photos.create') }}"
                        @click="loading = true"
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
                    <div x-data="{ showActions: false, showDropdown: false }" x-init="document.addEventListener('DOMContentLoaded', () => {
                        Livewire.hook('message.processed', () => {
                            Alpine.initTree(document.body);
                        });
                    });" @mouseenter.outside="showDropdown = false"
                        @click.away="showDropdown = false"
                        class="group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col">
                        <!-- Photo Container with Hover Actions (Desktop Only) -->
                        <div
                            class="relative flex-1 aspect-[4/3] flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                            <!-- Mobile Action Button -->
                            <div class="md:hidden absolute top-2 right-2 z-10">
                                <button type="button" @click="showDropdown = !showDropdown"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-900/40 text-white hover:bg-gray-900/60 focus:outline-none backdrop-blur-sm">
                                    <x-filament::icon icon="heroicon-m-ellipsis-vertical" class="w-5 h-5" />
                                </button>

                                <!-- Mobile Dropdown Menu -->
                                <div x-show="showDropdown" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-1 w-24 rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a wire:navigate
                                            href="{{ route('filament.admin.resources.photos.edit', $photo->getHashedId()) }}"
                                            @click="$dispatch('notification-cleared')"
                                            class="group flex items-center px-3 py-1.5 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <x-filament::icon icon="heroicon-m-pencil"
                                                class="mr-2 h-3.5 w-3.5 text-gray-400 group-hover:text-blue-500" />
                                            Edit
                                        </a>
                                        <button type="button" wire:click="$set('photoToDelete', {{ $photo->id }})"
                                            @click="$dispatch('open-modal', { id: 'confirm-delete-modal' }); showDropdown = false"
                                            class="group flex w-full items-center px-3 py-1.5 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <x-filament::icon icon="heroicon-m-trash"
                                                class="mr-2 h-3.5 w-3.5 text-gray-400 group-hover:text-red-500" />
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <img src="{{ Storage::disk('public')->url($photo->file_path) }}" alt="Photo"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy" />

                            <!-- Desktop Hover Overlay -->
                            <div
                                class="hidden md:flex absolute inset-0 bg-black/50 transition-all duration-300 opacity-0 group-hover:opacity-100">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="flex items-center space-x-4">
                                        <!-- Edit Button -->
                                        <a wire:navigate
                                            href="{{ route('filament.admin.resources.photos.edit', $photo->getHashedId()) }}"
                                            class="inline-flex items-center justify-center w-14 h-14 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-full transition-colors duration-200 shadow-lg transform hover:scale-105 active:scale-95"
                                            title="Edit Photo">
                                            <x-filament::icon icon="heroicon-o-pencil" class="w-7 h-7" />
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" wire:click="$set('photoToDelete', {{ $photo->id }})"
                                            @click="$dispatch('open-modal', { id: 'confirm-delete-modal' })"
                                            class="inline-flex items-center justify-center w-14 h-14 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white rounded-full transition-colors duration-200 shadow-lg transform hover:scale-105 active:scale-95"
                                            title="Delete Photo">
                                            <x-filament::icon icon="heroicon-o-trash" class="w-7 h-7" />
                                        </button>
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
