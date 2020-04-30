<?php

namespace Kompo\Exceptions;

use RuntimeException;

class KomposerNotDirectMethodException extends RuntimeException
{
	public function __construct($method, $komposerClass)
    {
        parent::__construct("The method [{$method}] is not a directly callable method in [{$komposerClass}].");
    }
}
