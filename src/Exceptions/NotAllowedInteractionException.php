<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAllowedInteractionException extends LogicException
{
    public function __construct($interactionType, $element)
    {
        parent::__construct("The interaction [{$interactionType}] is not allowed on Kompo [".class_basename($element).'s].');
    }
}
