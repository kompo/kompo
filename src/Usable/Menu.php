<?php

namespace Kompo;

use Kompo\Komposers\Komposer;
use Kompo\Komposers\Menu\MenuBooter;

abstract class Menu extends Komposer
{
    /**
     * Stores the menu komponents.
     *
     * @var array
     */
    public $komponents = [];

    /**
     * If the menu fixed or scrollable?
     *
     * @var bool
     */
    public $fixed;

    /**
     * The order of display of menus, integer between [1-4].
     * For example, a primary left sidebar would need $order = 1
     * If left blank, the default order is: Navbar > LeftSidebar > RightSidebar > Footer.
     *
     * @var int
     */
    public $order;

    /**
     * Constructs a Menu.
     *
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
    public function __construct(?array $store = [])
    {
        parent::__construct();

        $this->store($store);

        $this->boot(); //Menus boot on instantiation by default, since they cannot be called from Route::get()
    }

    /**
     * Get the Komponents displayed in the form.
     *
     * @return array
     */
    public function komponents()
    {
        return [];
    }

    /**
     * Get the request's validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Shortcut method to render a Menu into it's Vue component.
     *
     * @return string
     */
    public function renderNonStatic()
    {
        return MenuBooter::renderVueComponent($this);
    }

    /**
     * Shortcut method to boot a Komposer for display.
     *
     * @return string
     */
    public static function bootStatic($store = [])
    {
        return new static($store);
    }

    /**
     * Shortcut method to boot a Menu for display.
     *
     * @return string
     */
    public function bootNonStatic()
    {
        return MenuBooter::bootForDisplay($this);
    }
}
