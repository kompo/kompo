<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAKompoBaseElementException extends LogicException
{
    public function __construct($element)
    {
        $elementDescription = is_array($element) ? json_encode($element) : $element;

        parent::__construct("An attempt to render a non-kompo element inside the Komponent:     
[{$elementDescription}]");
    }
}
