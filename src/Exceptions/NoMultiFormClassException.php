<?php

namespace Kompo\Exceptions;

use RuntimeException;

class NoMultiFormClassException extends RuntimeException
{
    public function __construct($name)
    {
        parent::__construct("No Form class has been added to the Multiform [{$name}] komponent. Please refer to the documentation on how to attach a Form class to a MultiForm.");
    }
}
