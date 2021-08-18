<?php

namespace Kompo;

use Kompo\Core\AuthorizationGuard;
use Kompo\Core\KompoId;
use Kompo\Core\KompoInfo;
use Kompo\Komposers\Komposer;
use Kompo\Komposers\KomposerManager;
use Kompo\Routing\RouteFinder;

abstract class Menu extends Komposer
{
    /**
     * Stores the menu komponents.
     *
     * @var array
     */
    public $komponents = [];

    /**
     * The Vue komposer tag.
     *
     * @var string
     */
    public $vueKomposerTag = 'vl-menu';

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
     * Shortcut method to boot a Komposer for display.
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

        $this->komponents = KomposerManager::prepareKomponentsForDisplay($this, 'komponents', true);

        KompoId::setForKomposer($this);

        KompoInfo::saveKomposer($this);

        KomposerManager::booted($this);

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
