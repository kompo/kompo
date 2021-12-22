<?php

namespace Kompo;

use Kompo\Elements\Block;
use Kompo\Elements\Traits\OutputsHtml;

class I extends Block
{
    use OutputsHtml;

    public $vueComponent = 'I';

    public $htmlTag = 'i';
}
