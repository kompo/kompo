<?php

namespace Kompo\Exceptions;

use RuntimeException;

class RelationNotFoundException extends RuntimeException
{
    public function __construct($modelClass, $name)
	{
        parent::__construct("No attribute or relationship found in [{$modelClass}] for field [{$name}].");
    }
}
