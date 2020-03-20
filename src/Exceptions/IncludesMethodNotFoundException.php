<?php

namespace Kompo\Exceptions;

use RuntimeException;

class IncludesMethodNotFoundException extends RuntimeException
{
	public function __construct($method)
    {
        parent::__construct("No method [{$method}] found on form while trying to retrieve additional fields");
    }
}
