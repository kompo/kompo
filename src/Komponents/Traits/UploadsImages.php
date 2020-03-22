<?php 
namespace Kompo\Komponents\Traits;

use Intervention\Image\Facades\Image;

trait UploadsImages
{
    protected $withThumbnail = false;
    protected $resizeForWeb = true;

    /**
     * Sets the uploaded image(s) thumbnail's CSS height.
     *
     * @param     string   $height  The height in rem, px, %...
     *
     * @return self
     */
    public function thumbHeight($height)
    {
        return $this->data(['thumbHeight' => $height]);
    }

	/**
     * Does some common transformation on the $file object stored in DB.
     * (used for images)
     * @param  \Illuminate\Database\Eloquent\Model|Builder $file
     * @return \Illuminate\Database\Eloquent\Model|Builder
     */
	protected function transformFromDB($file)
	{
        $file['src'] = asset($file['path']);
        return $file;
	}

    /**
     * Validates the uploaded file is an image.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function validate($request)
    {
        return $request;
        /* Not working because when updating, existing files are objects... Need to write custom validation
        return $request->validate([
            $this->name.'.*' => 'image'
        ]);*/
    }


    /**
     * By default, the image component resizes the image to a web-friendly format with a maximum of 2000px width. To disable this feature, you may use this method.
     *
     * @return self
     */
    public function dontOptimzeForWeb()
    {
        $this->resizeForWeb = false;
        return $this;
    }

    /**
     * To also add a 300px wide thumbnail of the image, you may chain this method. 
     * The thumbnail will be stored in the same folder as the main image and will have '_thumb' appended to it's filename.
     * There's also a kompo helper `assetThumb()` available to easily display it. It works exactly the same as Laravel's `asset()` helper function except it targets the filename with '_thumb'.
     *
     * @return self
     */
    public function withThumbnail()
    {
        $this->withThumbnail = true;
        return $this;
    }

    protected function store($file, $record)
    {
        $file->store($this->storagePath($record));

        $imgRealPath = $this->realPath($this->publicHashPath($file, $record));

        if($this->resizeForWeb)
            $this->saveWebFriendly($imgRealPath);

        if($this->withThumbnail)
            $this->saveThumbnail($imgRealPath);        
    }

    protected function saveWebFriendly($path)
    {
        $this->resize($path, 2000)->save($path);
    }

    protected function saveThumbnail($path)
    {
        $this->resize($path, 300)->save(thumb($path));
    }

    protected function resize($path, $width)
    {
        return Image::make($path)->resize($width, null, function ($constraint) {
            $constraint->aspectRatio(); //auto height
            $constraint->upsize(); //prevent upsizing
        });
    }


}