<?php

namespace Kompo\Exceptions;

use RuntimeException;

class BadQueryDefinitionException extends RuntimeException
{
	public function __construct($query)
    {
        parent::__construct("The query is not well defined on [{$query}]. Please refer to the documentation for the possible ways of defining query queries.");
    }
}
