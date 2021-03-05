<?php

namespace Kompo\Exceptions;

use LogicException;

class NotOrderableQueryException extends LogicException
{
    public function __construct()
    {
        parent::__construct('The given query is not orderable since it is not coming from a Database.');
    }
}
