<?php

namespace App\Jobs;

use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResizePhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $photoId;

    /**
     * Create a new job instance.
     */
    public function __construct($photoId)
    {
        $this->photoId = $photoId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $photo = Photo::find($this->photoId);
        if ($photo && $photo->file_path) {
            $fileLocation = storage_path('app/public/' . $photo->file_path);
            if (!file_exists($fileLocation)) {
                return;
            }
            $maxFileSize = 1024 * 1024; // 1Mb
            if (filesize($fileLocation) <= $maxFileSize) {
                return;
            }
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
}
