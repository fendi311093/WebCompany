@php
    $photoId = $getState();
    $photoPath = null;

    if ($photoId) {
        $photo = \App\Models\Photo::find($photoId);
        if ($photo) {
            $photoPath = $photo->file_path;
        }
    }
@endphp

<div class="flex justify-center">
    @if ($photoPath)
        <div class="relative rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 mb-2 max-w-xs">
            <div class="w-full" style="max-height: 150px; overflow: hidden;">
                <img src="{{ asset('storage/' . $photoPath) }}" alt="Preview" class="object-contain w-full h-auto"
                    style="max-height: 150px;">
            </div>
            <div class="w-full bg-black bg-opacity-50 text-white p-1 text-xs truncate">
                {{ basename($photoPath) }}
            </div>
        </div>
    @else
        <div
            class="p-2 rounded-lg border border-gray-300 dark:border-gray-700 text-center text-gray-500 dark:text-gray-400 text-sm max-w-xs">
            Tidak ada foto yang dipilih
        </div>
    @endif
</div>

<script>
    // Tambahkan script untuk memastikan preview diperbarui saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Mengirim informasi bahwa halaman preview dimuat
        console.log('Preview photo component loaded');

        // Ambil state photo_id
        const photoIdSelect = document.querySelector('[name="data[photo_id]"]');
        if (photoIdSelect) {
            console.log('Nilai photo_id saat ini:', photoIdSelect.value);

            // Trigger update preview jika ada nilai
            if (photoIdSelect.value) {
                const event = new Event('change');
                photoIdSelect.dispatchEvent(event);
            }
        }
    });
</script>
