<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Collapse;
use Kompo\Link;
use Kompo\Menu;

class _SubMenuInstantiationChildMenu extends Menu
{
    public function render()
    {
        return [
            Collapse::form()->submenu(
                Link::form()->id('B'),
                null,
                Link::form()->id('C')
            ),
        ];
    }
}
