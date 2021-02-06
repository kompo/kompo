<?php

namespace Kompo;

use Kompo\Komponents\Block;
use Kompo\Komponents\Traits\OutputsHtml;

class I extends Block
{
	use OutputsHtml;
	
    public $vueComponent = 'I';

    public $htmlTag = 'i';
}
