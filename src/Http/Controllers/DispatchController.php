<?php

namespace Kompo\Http\Controllers;

use Kompo\Routing\Dispatcher;

class DispatchController
{
    public function __invoke()
    {
        return Dispatcher::dispatchConnection();
    }
}
