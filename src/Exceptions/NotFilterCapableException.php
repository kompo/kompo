<?php

namespace Kompo\Exceptions;

use LogicException;

class NotFilterCapableException extends LogicException
{
    public function __construct($component)
    {
        parent::__construct("This [{$component}] component does not perform filtering. Only Fields do. If you wish to filter with a Link or Button, please use LinkGroup or ButtonGroup instead.");
    }
}
