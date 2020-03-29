<?php

namespace Kompo\Exceptions;

use LogicException;

class NotFoundInteractionException extends LogicException
{
	public function __construct($interactionType)
    {
        parent::__construct("The interaction [{$interactionType}] either does not exist or is not supported.");
    }
}
