<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Collapse;
use Kompo\Input;
use Kompo\Menu;

class _SubMenuInstantiationMenu extends Menu
{
    public function render()
    {
        return Collapse::form()->submenu(
            Input::form()->id('A'),
            new _SubMenuInstantiationChildMenu(),
            null //to test filtering out
        );
    }
}
