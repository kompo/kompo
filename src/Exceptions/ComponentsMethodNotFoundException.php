<?php

namespace Kompo\Exceptions;

use RuntimeException;

class ComponentsMethodNotFoundException extends RuntimeException
{
	public function __construct($method, $komposer)
    {
        parent::__construct("No method [{$method}] found while trying to retrieve components for [{$komposer}]");
    }
}
