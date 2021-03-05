<?php

namespace Kompo;

use Kompo\Komponents\Traits\HasSubmenu;
use Kompo\Komponents\Trigger;

class CollapseOnMobile extends Trigger
{
    use HasSubmenu;

    public $bladeComponent = 'CollapseOnMobile';

    public $leftMenu = [];

    public $rightMenu = [];

    /**
     * Allows us to include a list of komponents in the left side of the navbar part that collapses on mobile. For example:
     * <php>CollapseOnMobile::leftMenu(
     * Link::form('Link 1'),
     * Link::form('Link 2')
     * )</php>
     *
     * @param array|args $args The komponents list. Can be written as an array or a list of method arguments.
     *
     * @return self ( description_of_the_return_value )
     */
    public function leftMenuNonStatic()
    {
        $this->leftMenu = $this->prepareMenu(func_get_args());

        return $this;
    }

    /**
     * Allows us to include a list of komponents in the right side of the navbar part that collapses on mobile. For example:
     * <php>CollapseOnMobile::rightMenu(
     * Link::form('Link 1'),
     * Link::form('Link 2')
     * )</php>
     *
     * @param array|args $args The komponents list. Can be written as an array or a list of method arguments.
     *
     * @return self ( description_of_the_return_value )
     */
    public function rightMenuNonStatic()
    {
        $this->rightMenu = $this->prepareMenu(func_get_args());

        return $this;
    }

    public static function leftMenuStatic()
    {
        return with(new static())->leftMenu(func_get_args());
    }

    public static function rightMenuStatic()
    {
        return with(new static())->rightMenu(func_get_args());
    }

    public static function duplicateStaticMethods()
    {
        return array_merge(parent::duplicateStaticMethods(), ['leftMenu', 'rightMenu']);
    }
}
