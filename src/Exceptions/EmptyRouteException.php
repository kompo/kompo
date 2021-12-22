<?php

namespace Kompo\Exceptions;

use RuntimeException;

class EmptyRouteException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Route or Url is empty on one of the elements.');
    }
}
