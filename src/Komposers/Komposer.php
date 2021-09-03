<?php

namespace Kompo\Komposers;

use Kompo\Core\KompoId;
use Kompo\Elements\Element;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Routing\Dispatcher;
use Kompo\Routing\Router;

abstract class Komposer extends Element
{
    use HasInteractions;
    use ForwardsInteraction;

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
        'store'      => [],
        'fields'     => [],
        'options'    => [],
    ];

    /**
     * The komposer's meta tags array that are displayed if the komposer is booted in a layout from route.
     *
     * @var array
     */
    protected $metaTags = [];

    /**
     * Specifications for pusher messages. An associate array where the key is the channel name and the value is a string or array of fully qualified Message classes.
     *
     * @var string[] array('EchoChannelName' => [MessageClass1::class, MessageClassName2::class])
     */
    public $pusherRefresh;

    /**
     * Constructs a Komposer.
     *
     * @return self
     */
    public function __construct()
    {
        $this->config([
            'sessionTimeoutMessage' => __('sessionTimeoutMessage'),
        ]);
    }

    /**
     * When a Komposer is called from a Route.
     *
     * @return mixed
     */
    public function __invoke()
    {
        $route = request()->route();
        $dispatcher = new Dispatcher($route->action['controller']);

        if ($layout = Router::getMergedLayout($route)) {
            $komposer = $dispatcher->bootKomposerForDisplay();

            return view('kompo::view', [
                'vueComponent'   => $komposer->render(),
                'containerClass' => property_exists($komposer, 'containerClass') ? $komposer->containerClass : 'container',
                'metaTags'       => $komposer->getMetaTags($komposer),
                'js'             => method_exists($komposer, 'js') ? $komposer->js() : null,
                'layout'         => $layout,
                'section'        => Router::getLastSection($route),
            ]);
        } else {
            return $dispatcher->bootKomposerForDisplay();
        }
    }

    /**
     * This method is fired at the very beginning of the booting process (even before created).
     * Handles booting authorization logic.
     *
     * @return bool Is booting the Komposer authorized or not?
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
     * @param mixed $data
     *
     * @return mixed
     */
    public function store($data = null)
    {
        return $this->_kompo('store', $data);
    }

    /**
     * Gets the route's parameter or the one persisted in the session.
     *
     * @param string|array|null $parameter
     *
     * @return mixed
     */
    public function parameter($data = null)
    {
        return $this->_kompo('parameters', $data);
    }

    /**
     * TODO: document and use throughout the examples.
     * Gets the prop of the Komposer, first we check if it's a route parameter, then we check if it is found in the store.
     *
     * @param string $parameter
     *
     * @return mixed
     */
    public function prop($data)
    {
        return $this->parameter($data) ?: $this->store($data);
    }

    /**
     * Do nothing for Querys and Menus.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {
        $this->boot();
    }

    /**
     * Do nothing for Querys and Menus.
     *
     * @return void
     */
    public function prepareForAction($komposer)
    {
        $this->boot();
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

    /**
     * Shortcut method to render a Komposer into it's Vue component.
     *
     * @return string
     */
    public static function renderStatic($store = [])
    {
        return static::boot($store)->render();
    }

    /**
     * Shortcut method to render a Komposer into it's Vue component.
     *
     * @return string
     */
    public function renderNonStatic()
    {
        return '<'.$this->vueKomposerTag.' :vkompo="'.htmlspecialchars($this).'"></'.$this->vueKomposerTag.'>';
    }

    /**
     * Shortcut method to boot a Komposer for display.
     *
     * @return string
     */
    public static function bootStatic($store = [])
    {
        return with(new static($store))->boot();
    }

    /**
     * Shortcut method to boot a Komposer for display.
     *
     * @return string
     */
    public function bootNonStatic()
    {
        return $this->bootForDisplay();
    }

    /**
     * Constructing a Komposer from the info sent by AJAX
     *
     * @param  array  $bootInfo  The boot information
     *
     * @return  self
     */
    public static function constructFromBootInfo($bootInfo)
    {
        $komposer = static::constructFromArray($bootInfo);

        $komposer->parameter($bootInfo['parameters']);

        KompoId::setForKomposer($komposer, $bootInfo);

        return $komposer;
    }

    /**
     * Constructing a Komposer from an array of information
     *
     * @param  array  $info  The array information
     *
     * @return  self
     */
    public static function constructFromArray($info)
    {
        return is_string($komposer = $info['kompoClass']) ? new $komposer($info['store']) : $komposer;
    }

    //TODO: document
    public function kompoId()
    {
        return KompoId::getFromElement($this);
    }

    /**
     * Methods that can be called both statically or non-statically.
     *
     * @return array
     */
    public static function duplicateStaticMethods()
    {
        return ['boot', 'render'];
    }
}
