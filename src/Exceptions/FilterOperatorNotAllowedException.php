<?php

namespace Kompo\Exceptions;

use LogicException;

class FilterOperatorNotAllowedException extends LogicException
{
	public function __construct($operator)
    {
        parent::__construct("The [{$operator}] operator is either not allowed or not supported yet. Please refer to the docs for the list of supported WHERE operators.");
    }
}
