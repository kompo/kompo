<?php

namespace Kompo;

use Kompo\Komposers\Menu\MenuBooter;
use Kompo\Komposers\Komposer;

abstract class Menu extends Komposer
{
    /**
     * Stores the menu komponents.
     *
     * @var array
     */
    public $komponents = [];

    /**
     * The CSS position of the menu.
     *
     * @var boolean
     */
    public $fixed;

    /**
     * Does the sidebar touch the top of the page or is it below the navbar?
     *
     * @var boolean
     */
    public $top = false;

    /**
     * Is the footer an outer or inner?
     *
     * @var boolean
     */
    public $out = false;

	/**
     * Constructs a Menu
     * 
     * @param null|array $store (optional) Additional data passed to the komponent.
     *
     * @return self
     */
	public function __construct($store = [], $dontBoot = false)
	{
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
