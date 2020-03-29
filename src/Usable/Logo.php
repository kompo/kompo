<?php

namespace Kompo;

use Kompo\Komponents\Traits\HasHref;
use Kompo\Komponents\Trigger;

class Logo extends Trigger
{
    use HasHref;

    public $menuComponent = 'Logo';

    public $imageUrl = false;

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);
    	$this->href('/');
    }

    /**
     * Adds an image for the logo by specifying the src (public url of the image).
     * Optionnally, you may add a width and/or height for a quicker set-up.
     *
     * @param      string  $public_url  The public url of the image.
     * @param      string  $width       The width value as in CSS. Ex: 40px or 6rem. Default is 70px.
     * @param      string  $height      The height as in CSS. Default is 'auto'
     *
     * @return     self 
     */
    public function imageNonStatic($public_url, $width = '70px', $height = 'auto')
    {
    	$this->imageUrl = url($public_url);
        $this->imageWidth = $width;
        $this->imageHeight = $height;
    	return $this;
    }

    public static function imageStatic($public_url, $width = '70px', $height = 'auto')
    {
        return with(new static())->imageNonStatic($public_url, $width, $height);
    }

    public static function duplicateStaticMethods()
    {
        return array_merge(parent::duplicateStaticMethods(), ['image']);
    }

    /**
     * Adds a class to the <img> tag in the logo.
     *
     * @param string  $class  The class
     *
     * @return self
     */
    public function imageClass($class)
    {
        return $this->data([
            'imageClass' => $class
        ]);
    }

}
