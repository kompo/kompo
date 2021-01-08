<?php

namespace Kompo\Komposers;

use Kompo\Elements\Element;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Routing\Dispatcher;
use Kompo\Routing\Router;

abstract class Komposer extends Element
{
    use HasInteractions, ForwardsInteraction;

    /**
     * The Vue component to render the Komposer as a child of another Komposer.
     *
     * @var string
     */
    public $vueComponent = 'Komposer';
    
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
     * When a Komposer is called from a Route
     *
     * @return  mixed
     */
    public function __invoke()
    {
        $route = request()->route();
        $dispatcher = new Dispatcher($route->action['controller']);

        if($layout = Router::getMergedLayout($route)){

            $komposer = $dispatcher->bootKomposerForDisplay();
            $booter = $dispatcher->booter;

            return view('kompo::view', [
                'vueComponent' => $booter::renderVueComponent($komposer),
                'containerClass' => property_exists($komposer, 'containerClass') ? $komposer->containerClass : 'container',
                'metaTags' => $komposer->getMetaTags($komposer),
                'js' => method_exists($komposer, 'js') ? $komposer->js() : null,
                'layout' => $layout,
                'section' => Router::getLastSection($route)
            ]);
        }else{
            return $dispatcher->bootKomposerForDisplay();
        }
    }

	/**
	 * This method is fired at the very beginning of the booting process (even before created).
	 * Handles booting authorization logic.
	 *
	 * @return boolean Is booting the Komposer authorized or not?
	 */
	public function authorizeBoot()
	{
		return true;
	}

    /**
     * Gets the failed authorization message, if defined.
     *
     * @return string
     */
    public function getFailedAuthorizationMessage()
    {
        return property_exists($this, 'failedAuthorizationMessage') ? $this->failedAuthorizationMessage : null;
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
     * @param  string|array|null  $parameter
     * @return mixed
     */
    public function parameter($data = null)
    {
        return $this->_kompo('parameters', $data);
    }

    /**
     * Do nothing for Querys and Menus.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {

    }

    /**
     * Do nothing for Querys and Menus.
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