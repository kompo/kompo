<?php

namespace Kompo\Exceptions;

use RuntimeException;

class BadQueryDefinitionException extends RuntimeException
{
	public function __construct($catalog)
    {
        parent::__construct("The query is not well defined on [{$catalog}]. Please refer to the documentation for the possible ways of defining catalog queries.");
    }
}
