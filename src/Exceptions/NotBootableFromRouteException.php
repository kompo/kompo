<?php

namespace Kompo\Exceptions;

use InvalidArgumentException;

class NotBootableFromRouteException extends InvalidArgumentException
{
    public function __construct($komponentClass)
    {
        parent::__construct("The class [{$komponentClass}] cannot be booted from a route with kompo.");
    }
}
