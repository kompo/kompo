<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAcceptableInteractionException extends LogicException
{
    public function __construct($interactions)
    {
        parent::__construct('The interaction trigger(s) should either be a String or Array. Instead a ['.gettype($interactions).'] was given.');
    }
}
