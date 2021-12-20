<?php

namespace Kompo;

use Kompo\Komponents\Block;

class Img extends Block
{
    public $vueComponent = 'Img';
    public $bladeComponent = 'Img';

    public $src;

    protected function vlInitialize($label)
    {
        $this->src = filter_var($label, FILTER_VALIDATE_URL) ? $label : asset($label);

        parent::vlInitialize('');
    }

    //TODO Document
    public function bgCover($bgPosition = 'center')
    {
        $this->vueComponent = 'ImgCover';

        return $this->config([
            'bgPosition' => $bgPosition,
        ]);
    }

    //TODO Document
    public function alt($alt)
    {
        return $this->config([
            'alt' => $alt,
        ]);
    }
}
