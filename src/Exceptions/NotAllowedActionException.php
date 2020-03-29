<?php

namespace Kompo\Exceptions;

use LogicException;

class NotAllowedActionException extends LogicException
{
	public function __construct($methodName, $element)
    {
        parent::__construct("The action [{$methodName}] is not allowed on Kompo [".class_basename($element)."s].");
    }
}
