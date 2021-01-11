<?php

namespace Kompo;

use Kompo\Komponents\Block;
use Kompo\Komponents\Traits\OutputsHtml;

class Html extends Block
{
	use OutputsHtml;
	
    public $vueComponent = 'Html';
    public $bladeComponent = 'Html';

    public $htmlTag = 'div';
    
}
