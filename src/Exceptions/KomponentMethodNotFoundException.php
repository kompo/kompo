<?php

namespace Kompo\Exceptions;

use RuntimeException;

class KomponentMethodNotFoundException extends RuntimeException
{
    public function __construct($method, $komponent)
    {
        parent::__construct("No method [{$method}] found on komponent class [".get_class($komponent).'].');
    }
}
