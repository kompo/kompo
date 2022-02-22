<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHandler extends FileHandler
{
    /**
     * The specified disk for uploaded files.
     * By default (see config file), it is stored in the 'local' disk for _File() and 'public' disk for _Image().
     */
    protected $disk;

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

    protected function initializeDisk()
    {
        $this->disk = config('kompo.default_storage_disk.image');
    }

    /**
     * Stores on disk.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string                       $storePath The path where the file will be stored
     */
    protected function storeOnDisk($file, $modelPath)
    {
        $filename = $this->getStoredFileName($file, $modelPath);

        $this->setFilePath($modelPath, $filename);

        if ($this->optimizeForWeb) {
            Storage::disk($this->disk)->put(
                $this->filePath,
                $this->resize($file, $this->optimizeForWeb === true ? 2000 : $this->optimizeForWeb),
                $this->visibility
            );
        } else {
            Storage::disk($this->disk)->putFileAs(
                $modelPath, 
                $file, 
                $filename, 
                $this->visibility
            );
        }

        if ($this->withThumbnail) {
            Storage::disk($this->disk)->put(
                thumb($this->filePath),
                $this->resize($file, $this->withThumbnail === true ? 300 : $this->withThumbnail),
                $this->visibility
            );
        }
    }

    protected function resize($file, $width)
    {
        $image = Image::make($file)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio(); //auto height
            $constraint->upsize(); //prevent upsizing
        })->encode()->__toString();

        return $image;
    }

    protected function storageDelete($filePath)
    {
        parent::storageDelete($filePath);
        Storage::disk($this->disk)->delete(thumb($filePath));
    }
}
