<x-filament-panels::page>

    <div class="mb-4 p-4 bg-primary-50 dark:bg-primary-950 rounded-lg border border-primary-200 dark:border-primary-800">
        <h2 class="text-xl font-bold text-primary-800 dark:text-primary-200 mb-2">How to Upload Photos</h2>
        <ol class="list-decimal ml-6 text-primary-700 dark:text-primary-300 space-y-1">
            <li>Click the area below or the "Browse" button to select a photo.</li>
            <li>You can select multiple photos at once.</li>
            <li>Each photo will be saved with a unique ID in the database.</li>
            <li>Click the "Upload" button to upload the selected photos.</li>
        </ol>
    </div>

    <div class="w-full">
        {{ $this->form }}
    </div>

</x-filament-panels::page>
