<?php

namespace Kompo;

class Blade extends Html
{
    public function __construct($label, $vars = [])
    {
        parent::__construct(view($label, $vars)->render());
    }
}
