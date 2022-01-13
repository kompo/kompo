<?php

namespace Kompo\Exceptions;

use RuntimeException;

class KomponentMethodNotAllowedException extends RuntimeException
{
    public function __construct($method, $komponent)
    {
        parent::__construct("The method [{$method}] cannot be called by AJAX in Komponent [".get_class($komponent).'].');
    }
}
