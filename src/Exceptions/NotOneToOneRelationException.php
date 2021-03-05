<?php

namespace Kompo\Exceptions;

use RuntimeException;

class NotOneToOneRelationException extends RuntimeException
{
    public function __construct($initialName, $relationName)
    {
        parent::__construct("You cannot use the name attribute [{$initialName}] because [{$relationName}] is not a OneToOne relationship.");
    }
}
