<?php

namespace Kompo\Exceptions;

use LogicException;

class NotApplicableInteractionException extends LogicException
{
	public function __construct($interactionType, $element)
    {
        parent::__construct("There are no actions to apply the interaction [{$interactionType}] on Kompo [".class_basename($element)."s].");
    }
}
