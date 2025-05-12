<x-filament-panels::page>
    <div class="mb-4 p-4 bg-primary-50 dark:bg-primary-950 rounded-lg border border-primary-200 dark:border-primary-800">
        <h2 class="text-xl font-bold text-primary-800 dark:text-primary-200 mb-2">Petunjuk Upload Foto</h2>
        <ol class="list-decimal ml-6 text-primary-700 dark:text-primary-300 space-y-1">
            <li>Klik area di bawah untuk memilih foto (dapat memilih lebih dari satu foto)</li>
            <li>Foto dapat diatur ulang urutannya dengan drag and drop</li>
            <li>Gunakan editor foto untuk mengatur ukuran jika diperlukan</li>
            <li>Klik tombol "Upload Semua Foto" di bawah setelah selesai memilih</li>
        </ol>
    </div>

    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" color="success" icon="heroicon-o-arrow-up-tray" size="lg"
                class="w-full justify-center py-3">
                Upload Semua Foto
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
