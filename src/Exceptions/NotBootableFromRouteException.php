<?php

namespace Kompo\Exceptions;

use InvalidArgumentException;

class NotBootableFromRouteException extends InvalidArgumentException
{
    public function __construct($elementClass)
    {
        parent::__construct("The class [{$elementClass}] cannot be booted from a route with kompo.");
    }
}
