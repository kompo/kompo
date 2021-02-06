<?php

namespace Kompo;

use Kompo\Komposers\Komposer;
use Kompo\Komposers\Menu\MenuBooter;
use Kompo\Routing\Router;

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
     * @var boolean
     */
    public $fixed;

    /**
     * The order of display of menus, integer between [1-4]. 
     * For example, a primary left sidebar would need $order = 1
     * If left blank, the default order is: Navbar > LeftSidebar > RightSidebar > Footer.
     *
     * @var integer
     */
    public $order;

	/**
     * Constructs a Menu
     * 
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct(?array $store = [], $dontBoot = false)
	{
        if(Router::shouldNotBeBooted()) return; //request has not been handled yet
        
        if(!$dontBoot)
            MenuBooter::bootForDisplay($this, $store);
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
    public static function render($store = [])
    {
        return MenuBooter::renderVueComponent(new static($store));
    }

}
