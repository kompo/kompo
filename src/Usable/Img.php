<?php

namespace Kompo;

use Kompo\Komponents\Block;

class Img extends Block
{
    public $vueComponent = 'Img';
    public $bladeComponent = 'Img';

    protected function vlInitialize($label)
    {
        $label = filter_var($label, FILTER_VALIDATE_URL) ? $label : asset($label);

        parent::vlInitialize($label);
    }

    //TODO Document
    public function bgCover()
    {
        $this->vueComponent = 'ImgCover';

        return $this;
    }

    //TODO Document
    public function alt($alt)
    {
        return $this->config([
            'alt' => $alt,
        ]);
    }
}
