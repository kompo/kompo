<?php

namespace Kompo\Komposers;

use Kompo\Elements\Element;

abstract class Komposer extends Element
{
    /**
     * The meta komponent's data for internal usage. Contains the store, route parameters, etc...
     *
     * @var array
     */
    protected $_kompo = [
        'parameters' => [],
        'store' => [],
        'fields' => [],
        'options' => []
    ];

    /**
     * The komposer's meta tags array that are displayed if the komposer is booted in a layout from route.
     *
     * @var array
     */
    protected $metaTags = [];

	/**
	 * This method is fired at the very beginning of the booting process (even before created).
	 * Handles booting authorization logic.
	 *
	 * @return boolean Is booting the Komposer authorized or not?
	 */
	public function bootAuthorization()
	{
		return true;
	}

    /**
     * Assign additional session data to the komposer. Or retrieve it if parameter is a string key.
     *
     * @param  mixed  $data
     * @return mixed
     */
    public function store($data = null)
    {
        return $this->_kompo('store', $data);
    }

    /**
     * Gets the route's parameter or the one persisted in the session.
     *
     * @param  mixed  $parameter
     * @return mixed
     */
    public function parameter($data = null)
    {
        return $this->_kompo('parameters', $data);
    }

    /**
     * Do nothing for Catalogs and Menus.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {

    }

    /**
     * Do nothing for Catalogs and Menus.
     *
     * @return void
     */
    public function prepareForAction($komposer)
    {

    }

    /**
     * The komposer's meta tags array that are displayed if the komposer is booted in a layout from route.
     * Can be overriden.
     *
     * @var array
     */
    public function getMetaTags()
    {
        return ($this->metaTags && count($this->metaTags)) ? $this->metaTags : null;
    }
    
}