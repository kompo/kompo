<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\Collapse;
use Kompo\Link;
use Kompo\Menu;

class _SubMenuInstantiationChildMenu extends Menu
{
    public function komponents()
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
