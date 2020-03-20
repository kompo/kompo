<?php

namespace Kompo\Exceptions;

use RuntimeException;

class BadTriggerDefinitionException extends RuntimeException
{
	public function __construct($type)
    {
        parent::__construct("The parameter for trigger(s) should be either a String or Array. Instead a [{$type}] was given.");
    }
}
