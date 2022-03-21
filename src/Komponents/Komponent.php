<?php

namespace Kompo\Komponents;

use Kompo\Core\KompoId;
use Kompo\Core\Util;
use Kompo\Elements\BaseElement;
use Kompo\Exceptions\NotAKompoBaseElementException;
use Kompo\Interactions\Traits\ForwardsInteraction;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Routing\Dispatcher;
use Kompo\Routing\Router;

abstract class Komponent extends BaseElement
{
    use HasInteractions;
    use ForwardsInteraction;

    /**
     * The Vue component to render the Komponent as a child of another Komponent.
     *
     * @var string
     */
    public $vueComponent = 'Komponent';

    /**
     * The meta element's data for internal usage. Contains the store, route parameters, etc...
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
     * The komponent's meta tags array that are displayed if the komponent is booted in a layout from route.
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
     * Constructs a Komponent.
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
     * When a Komponent is called from a Route.
     *
     * @return mixed
     */
    public function __invoke()
    {
        $route = request()->route();
        $dispatcher = new Dispatcher($route->action['controller']);

        if ($layout = Router::getMergedLayout($route)) {
            $komponent = $dispatcher->bootKomponentForDisplay();

            return view('kompo::view', [
                'vueComponent'   => $komponent->toHtml(),
                'containerClass' => property_exists($komponent, 'containerClass') ? $komponent->containerClass : 'container',
                'metaTags'       => $komponent->getMetaTags($komponent),
                'js'             => method_exists($komponent, 'js') ? $komponent->js() : null,
                'layout'         => $layout,
                'section'        => Router::getLastSection($route),
            ]);
        } else {
            return $dispatcher->bootKomponentForDisplay();
        }
    }

    /**
     * This method is fired at the very beginning of the booting process (even before created).
     * Handles booting authorization logic.
     *
     * @return bool Is booting the Komponent authorized or not?
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
     * Assign additional session data to the komponent. Or retrieve it if parameter is a string key.
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
     * Gets the prop of the Komponent, first we check if it's a route parameter, then we check if it is found in the store.
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
    public function prepareForDisplay($komponent)
    {
        $this->boot();
    }

    /**
     * Do nothing for Querys and Menus.
     *
     * @return void
     */
    public function prepareForAction($komponent)
    {
        $this->boot();
    }

    /**
     * The komponent's meta tags array that are displayed if the komponent is booted in a layout from route.
     * Can be overriden.
     *
     * @var array
     */
    public function getMetaTags()
    {
        return ($this->metaTags && count($this->metaTags)) ? $this->metaTags : null;
    }

    /**
     * Shortcut method to output the Komponent into it's HTML Vue tag.
     *
     * @return string
     */
    public static function toHtmlStatic($store = [])
    {
        return static::boot($store)->toHtml();
    }

    /**
     * Shortcut method to output the Komponent into it's HTML Vue tag.
     *
     * @return string
     */
    public function toHtmlNonStatic()
    {
        return '<'.$this->vueKomponentTag.' :vkompo="'.htmlspecialchars($this).'"></'.$this->vueKomponentTag.'>';
    }

    /**
     * Shortcut method to boot a Komponent for display.
     *
     * @return string
     */
    public static function bootStatic($store = [])
    {
        return with(new static($store))->boot();
    }

    /**
     * Shortcut method to boot a Komponent for display.
     *
     * @return string
     */
    public function bootNonStatic()
    {
        return $this->bootForDisplay();
    }

    /**
     * Constructing a Komponent from the info sent by AJAX
     *
     * @param  array  $bootInfo  The boot information
     *
     * @return  self
     */
    public static function constructFromBootInfo($bootInfo)
    {
        $komponent = static::constructFromArray($bootInfo);

        $komponent->parameter($bootInfo['parameters']);

        KompoId::setForKomponent($komponent, $bootInfo);

        return $komponent;
    }

    /**
     * Constructing a Komponent from an array of information
     *
     * @param  array  $info  The array information
     *
     * @return  self
     */
    public static function constructFromArray($info)
    {
        return is_string($komponent = $info['kompoClass']) ? new $komponent($info['store']) : $komponent;
    }

    /**
     * Loops over an array of elements specified in the Komponent (render, top, bottom, ...) before the display phase
     *
     * @param null|array|Collection|Element  $renderedElements  The rendered elements
     *
     * @return Illuminate\Support\Collection
     */
    public function prepareOwnElementsForDisplay($renderedElements)
    {
        return Util::collect($renderedElements)->filter()->each(function ($element) {
            
            if (!$element instanceof BaseElement) {
                throw new NotAKompoBaseElementException($element);
            }

            $element->prepareForDisplay($this);

            $element->mountedHook($this);
        })->values()->all();
    }

    /**
     * Loops over an array of elements specified in the Komponent (render, top, bottom, ...) before the action phase
     *
     * @param null|array|Collection|Element  $renderedElements  The rendered elements
     *
     * @return Illuminate\Support\Collection
     */
    public function prepareOwnElementsForAction($renderedElements)
    {
        return Util::collect($renderedElements)->filter()->each(function ($element) {
            
            if (!$element instanceof BaseElement) {
                throw new NotAKompoBaseElementException($element);
            }

            $element->prepareForAction($this);

            $element->mountedHook($this);
        })->values()->all();
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
        return ['boot', 'toHtml'];
    }
}
