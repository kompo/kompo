<?php

namespace Kompo\Exceptions;

use RuntimeException;

class NotFoundKompoActionException extends RuntimeException
{
	public function __construct($kompoClass)
    {
        parent::__construct("No suitable action was specified for Komposer class [{$kompoClass}].");
    }
}
