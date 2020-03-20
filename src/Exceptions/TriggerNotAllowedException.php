<?php

namespace Kompo\Exceptions;

use RuntimeException;

class TriggerNotAllowedException extends RuntimeException
{
	public function __construct($trigger)
    {
        parent::__construct("[{$trigger}] is not an allowed trigger name.");
    }
}
