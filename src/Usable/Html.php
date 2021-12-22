<?php

namespace Kompo;

use Kompo\Elements\Block;
use Kompo\Elements\Traits\OutputsHtml;

class Html extends Block
{
    use OutputsHtml;

    public $vueComponent = 'Html';
    public $bladeComponent = 'Html';

    public $htmlTag = 'div';
}
