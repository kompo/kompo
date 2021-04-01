<?php

namespace Kompo\Komponents\Traits;

use Kompo\Komponents\Managers\LayoutManager;

trait HasSubmenu
{
    /**
     * Stores the child komponents from the submenu.
     *
     * @var array
     */
    public $komponents = [];

    /**
     * Prepares the child komponents of the layout.
     *
     * @param Komposer
     */
    public function prepareForDisplay($komposer)
    {
        collect($this->komponents)->each(function ($komponent) use ($komposer) {

            $komponent->prepareForDisplay($komposer);

            $komponent->mountedHook($komposer);

        });
    }

    /**
     * Prepares the child komponents of the layout.
     *
     * @param Komposer
     */
    public function prepareForAction($komposer)
    {
        collect($this->komponents)->each(function ($komponent) use ($komposer) {

            $komponent->prepareForAction($komposer);

            $komponent->mountedHook($komposer);
            
        });
    }




    protected function prepareMenu($args)
    {
        return LayoutManager::collectFilteredKomponents($args, $this)->each(function ($component) {
            $this->prepareHashAndActiveState($component);
        })->values()->all();
    }

    public function prepareHashAndActiveState($component)
    {
        if (method_exists($component, 'getHash') && $component->getHash() && $component->href == 'javascript:void(0)') {
            $component->href($this->href) //so that turbolinnks are added
                ->addHash($component->getHash()) //to remove the need of repeating href for each child
                ->class('vl-has-hash');
        }

        //if a link in the menu is active, then the parent is active (even if no href for the parent)
        if ($component->config('active')) {
            $this->config(['active' => 'vlActive']);
        }
    }

    public function submenu(...$args)
    {
        $this->komponents = $this->prepareMenu(func_get_args());

        return $this;
    }

    public function prependMenu($args)
    {
        $this->komponents = $this->prepareMenu(array_merge(func_get_args(), $this->komponents));

        return $this;
    }

    /**
     * The collapsible menu will be opened on page load.
     *
     * @return self
     */
    public function expandByDefault()
    {
        return $this->config(['expandByDefault' => true]);
    }

    /**
     * The collapsible menu will open if the user is on one of the pages in it's submenu.
     *
     * @return self
     */
    public function expandIfActive()
    {
        return $this->config(['expandIfActive' => true]);
    }

    /**
     * The collapsible menu will not have a caret.
     *
     * @return self
     */
    public function noCaret()
    {
        return $this->config(['noCaret' => true]);
    }
}
