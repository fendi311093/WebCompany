<?php

namespace App\Jobs;

use App\Models\Photo;
use App\Models\Content;
use App\Models\Profil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResizePhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $modelId;
    protected $modelType;
    protected $photoField;

    /**
     * Create a new job instance.
     */

    // Menggunakan modelId, modelType, dan photoField untuk menentukan model dan field yang akan di-resize
    // modelId: ID dari model yang akan di-resize
    // modelType: Tipe model (misalnya 'Photo' atau 'Content')
    // photoField: Nama field yang berisi path foto (default 'file_path' untuk Photo, 'photo' untuk Content)
    // Contoh penggunaan: new ResizePhotoJob($photo->id, 'Photo', 'file_path');
    // Contoh penggunaan: new ResizePhotoJob($content->id, 'Content', 'photo');
    public function __construct($modelId, $modelType = 'Photo', $photoField = 'file_path')
    {
        $this->modelId = $modelId;
        $this->modelType = $modelType;
        $this->photoField = $photoField;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $model = null;
        $filePath = null;

        switch ($this->modelType) {
            case 'Photo':
                $model = Photo::find($this->modelId);
                $filePath = $model?->file_path;
                break;
            case 'Content':
                $model = Content::find($this->modelId);
                $filePath = $model?->photo;
                break;
            case 'Profil':
                $model = Profil::find($this->modelId);
                $filePath = $model?->photo;
                break;
            default:
                return;
        }

        if (!$model || !$filePath) {
            return;
        }

        $fileLocation = storage_path('app/public/' . $filePath);
        if (!file_exists($fileLocation)) {
            return;
        }

        $maxFileSize = 1024 * 1024; // 1Mb

        // Tidak perlu lagi karena sudah ada pengecekan di model PHOTO
        // if (filesize($fileLocation) <= $maxFileSize) {
        //     return;
        // }

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($fileLocation);
        $image->scale(width: 800);

        $quality = 80;
        while (filesize($fileLocation) > $maxFileSize && $quality >= 30) {
            $image->save($fileLocation, quality: $quality);
            clearstatcache(true, $fileLocation);
            $quality -= 5;
        }
    }
}
