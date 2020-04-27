<?php

namespace Kompo\Exceptions;

use RuntimeException;

class KomposerMethodNotFoundException extends RuntimeException
{
	public function __construct($method)
    {
        parent::__construct("No method [{$method}] found on komposer.");
    }
}
