<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHandler extends FileHandler
{
    /**
     * For Kompo\Image: max width in px of full image.
     * intervention/image does resizing.
     * Set to false to deactivate resizing.
     *
     * @var int|bool
     */
    public $optimizeForWeb = 2000;

    /**
     * Create an image thumbnail along with the full image.
     * intervention/image does resizing.
     * Set an integer for thumbnail width in px.
     *
     * @var int|bool
     */
    public $withThumbnail = false;

    /**
     * Stores on disk.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string                       $storePath The path where the file will be stored
     */
    protected function storeOnDisk($file, $modelPath)
    {
        if ($this->optimizeForWeb) {
            Storage::disk($this->disk)->put(
                $this->getStoragePath($file, $modelPath),
                $this->resize($file, $this->optimizeForWeb === true ? 2000 : $this->optimizeForWeb),
                $this->visibility
            );
        } else {
            Storage::disk($this->disk)->put($modelPath, $file, $this->visibility);
        }

        if ($this->withThumbnail) {
            Storage::disk($this->disk)->put(
                thumb($this->getStoragePath($file, $modelPath)),
                $this->resize($file, $this->withThumbnail === true ? 300 : $this->withThumbnail),
                $this->visibility
            );
        }
    }

    protected function resize($file, $width)
    {
        return Image::make($file)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio(); //auto height
            $constraint->upsize(); //prevent upsizing
        })->encode()->__toString();
    }

    protected function storageDelete($filePath)
    {
        parent::storageDelete($filePath);
        Storage::disk($this->disk)->delete(thumb($filePath));
    }
}
