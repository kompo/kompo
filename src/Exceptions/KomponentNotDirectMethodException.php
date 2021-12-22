<?php

namespace Kompo\Exceptions;

use RuntimeException;

class KomponentNotDirectMethodException extends RuntimeException
{
    public function __construct($method, $komponentClass)
    {
        parent::__construct("The method [{$method}] is not a directly callable method in [{$komponentClass}].");
    }
}
