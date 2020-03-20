<?php

namespace Kompo\Exceptions;

use RuntimeException;

class FormMethodNotFoundException extends RuntimeException
{
	public function __construct($method)
    {
        parent::__construct("No method [{$method}] found on form while trying to POST to form.");
    }
}
