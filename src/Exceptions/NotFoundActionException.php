<?php

namespace Kompo\Exceptions;

use LogicException;

class NotFoundActionException extends LogicException
{
    public function __construct($actionType)
    {
        parent::__construct("The action [{$actionType}] was not found in the list of allowed actions.");
    }
}
