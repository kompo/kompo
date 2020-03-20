<?php

namespace Kompo\Exceptions;

use LogicException;

class RouteLayoutIncorrectlySetException extends LogicException
{
    public function __construct($uri)
	{
        parent::__construct("Bad Logic: The layout method is not usable on route [{$uri}]. Extend the layout in Route groups instead, ex: Route::layout('app')->group().");
    }
}


