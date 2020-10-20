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

    protected function prepareMenu($args)
    {
        return LayoutManager::collectFilteredKomponents($args, $this)->each(function($component) { 

            $this->prepareHashAndActiveState($component);

        })->values()->all();
    }

    public function prepareHashAndActiveState($component)
    {
        if( method_exists($component, 'getHash') && $component->getHash() && $component->href == 'javascript:void(0)')
            $component->href($this->href) //so that turbolinnks are added
                ->addHash($component->getHash()) //to remove the need of repeating href for each child
                ->class('vl-has-hash');

        //if a link in the menu is active, then the parent is active (even if no href for the parent)
        if($component->data('active')) 
            $this->data(['active' => 'vlActive']);
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
     * The dropdown menu will align to the right instead of the default left alignment.
     *
     * @return     self 
     */
    public function alignRight()
    {
        return $this->data([ 'vl-dropdown-menu-right' => 'vl-dropdown-menu-right' ]);
    }

    /**
     * The collapsible menu will be opened on page load.
     *
     * @return     self 
     */
    public function expandByDefault()
    {
        return $this->data(['expandByDefault' => true]);
    }

    /**
     * The collapsible menu will open if the user is on one of the pages in it's submenu.
     *
     * @return     self
     */
    public function expandIfActive()
    {
        return $this->data(['expandIfActive' => true]);
    }

}