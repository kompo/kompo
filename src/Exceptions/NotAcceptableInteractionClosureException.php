<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAcceptableInteractionClosureException extends LogicException
{
	public function __construct($actionOrClosure)
    {
        parent::__construct("The action is neither a Closure, nor an Action instance. Instead a [".gettype($actionOrClosure)."] was given.");
    }
}
