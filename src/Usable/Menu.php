<?php

namespace Kompo;

use Kompo\Komposers\Menu\MenuBooter;
use Kompo\Komposers\Komposer;

abstract class Menu extends Komposer
{
    /**
     * Stores the form components.
     *
     * @var array
     */
    public $components = [];

    /**
     * The CSS position of the menu.
     *
     * @var array
     */
    public $fixed;

    /**
     * Does the sidebar touch the top of the page or is it below the navbar?
     *
     * @var array
     */
    public $top = false;

    /**
     * Stores the form components.
     *
     * @var array
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
     * Get the Components displayed in the form.
     *
     * @return array
     */
    public function components()
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
        return MenuBooter::renderVueComonent(new static($store));
    }

}
