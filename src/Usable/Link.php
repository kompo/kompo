<?php

namespace Kompo;

use Kompo\Elements\Traits\HasHref;
use Kompo\Elements\Traits\TriggerStyles;
use Kompo\Elements\Trigger;

class Link extends Trigger
{
    use HasHref;
    use TriggerStyles;

    public $vueComponent = 'Link';
    public $bladeComponent = 'Link';
}
