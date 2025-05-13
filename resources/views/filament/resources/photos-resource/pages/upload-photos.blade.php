<x-filament-panels::page>
    <div class="mb-4 p-4 bg-primary-50 dark:bg-primary-950 rounded-lg border border-primary-200 dark:border-primary-800">
        <h2 class="text-xl font-bold text-primary-800 dark:text-primary-200 mb-2">Cara Upload Foto</h2>
        <ol class="list-decimal ml-6 text-primary-700 dark:text-primary-300 space-y-1">
            <li>Klik area di bawah atau tombol "Browse" untuk memilih foto</li>
            <li>Anda dapat memilih lebih dari satu foto sekaligus</li>
            <li>Setiap foto akan disimpan dengan ID unik di database</li>
            <li>Klik tombol "Upload" setelah selesai memilih</li>
        </ol>
    </div>

    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="mt-6 flex justify-center">
            <x-filament::button type="submit" color="success" icon="heroicon-o-arrow-up-tray" size="lg"
                class="px-8 py-2" wire:loading.attr="disabled" wire:target="create">
                <span wire:loading wire:target="create">Uploading...</span>
                <span wire:loading.remove wire:target="create">Upload</span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
