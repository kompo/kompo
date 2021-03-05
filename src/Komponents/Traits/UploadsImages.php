<?php

namespace Kompo\Komponents\Traits;

trait UploadsImages
{
    /**
     * Sets the Image Field thumbnails CSS height. This is independant from the back-end thumbnail resizing.
     *
     * @param string $height The height in rem, px, %...
     *
     * @return self
     */
    public function thumbHeight($height)
    {
        return $this->config(['thumbHeight' => $height]);
    }

    /**
     * Disable default preview functionality of image thumbnails in a modal.
     *
     * @return self
     */
    public function noPreview()
    {
        return $this->config(['thumbPreviewDisabled' => true]);
    }

    /**
     * Set a max width to resize image uploads or set to false to disable resizing.
     *
     * @param int|bool $width The width
     *
     * @return self
     */
    public function resize($width = 2000)
    {
        $this->fileHandler->optimizeForWeb = $width;

        return $this;
    }

    /**
     * By default, the image component resizes the image to a web-friendly format with a maximum of 2000px width. To disable this feature, you may use this method.
     *
     * @return self
     */
    public function noResize()
    {
        $this->fileHandler->optimizeForWeb = false;

        return $this;
    }

    /**
     * To also add a thumbnail of the image, you may chain this method.
     * The thumbnail will be stored in the same folder as the main image and will have '_thumb' appended to it's filename.
     * There's also a kompo helper `assetThumb()` available to easily display it. It works exactly the same as Laravel's `asset()` helper function except it targets the filename with '_thumb'.
     *
     * @param int|bool $thumbWidth The width in px of the thumbnail. Default is 300px
     *
     * @return self
     */
    public function withThumbnail($thumbWidth = 300)
    {
        $this->fileHandler->withThumbnail = $thumbWidth;

        return $this;
    }

    /**
     * TODO: document on website: confirm removal of image.
     *
     * @param <type> $message The message
     *
     * @return <type> ( description_of_the_return_value )
     */
    public function confirmDelete($message = null)
    {
        return $this->config([
            'confirmDelete' => __($message) ?: __('Are you sure you want to remove this image?'),
        ]);
    }

    /**
     * Does some common transformation on the $file object stored in DB.
     * (used for images).
     *
     * @param \Illuminate\Database\Eloquent\Model|Builder $file
     *
     * @return \Illuminate\Database\Eloquent\Model|Builder
     */
    protected function transformFromDB($file)
    {
        $file['src'] = asset($file['path']);

        return $file;
    }

    protected function convertBackToDb($requestValue)
    {
        $decodedFromFront = json_decode($requestValue, true);
        unset($decodedFromFront['src']);

        return $decodedFromFront;
    }

    /**
     * Validates the uploaded file is an image.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function validate($request)
    {
        /* Not working because when updating, existing files are objects... Need to write custom validation
        return $request->validate([
            $this->name.'.*' => 'image'
        ]);*/
    }
}
