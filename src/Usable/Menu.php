<?php

namespace Kompo;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Komponents\Komponent;
use Kompo\Komponents\KomponentManager;
use Kompo\Routing\RouteFinder;

abstract class Menu extends Komponent
{
    /**
     * Stores the menu elements.
     *
     * @var array
     */
    public $elements = [];

    /**
     * The Vue komponent tag.
     *
     * @var string
     */
    public $vueKomponentTag = 'vl-menu';

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
     * @param null|array $store (optional) Additional data passed to the Komponent.
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
     * Get the elements displayed in the menu.
     *
     * @return array
     */
    public function render()
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
     * Shortcut method to boot a Komponent for display.
     *
     * @return string
     */
    public static function bootStatic($store = [])
    {
        return new static($store);
    }

    /**
     * Initial boot of a Menu Komponent for display.
     *
     * @return self
     */
    public function bootForDisplay($routeParams = null)
    {
        $this->parameter($routeParams ?: RouteFinder::getRouteParameters());

        AuthorizationGuard::checkBoot($this, 'Display');

        $this->elements = KomponentManager::prepareElementsForDisplay($this, 'render', true);

        KompoId::setForKomponent($this);

        KompoInfo::saveKomponent($this);

        KomponentManager::booted($this);

        return $this;
    }

    /**
     * Subsequent boot of a Menu Komponent for a later action (i.e. ajax request, etc...)
     *
     * @return self
     */
    public function bootForAction()
    {
        AuthorizationGuard::checkBoot($this, 'Action');

        return $this;
    }
}
