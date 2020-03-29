<?php 

namespace Kompo\Komponents\Traits;

use Kompo\Komponents\LayoutManager;

trait HasSubmenu
{
    /**
     * Stores the child components from the submenu.
     *
     * @var array
     */
    public $components = [];

    protected function prepareMenu($args)
    {
        return LayoutManager::collectFilteredComponents($args, $this)->each(function($component) { 

            $this->prepareHashAndActiveState($component);

        })->values()->all();
    }

    public function prepareHashAndActiveState($component)
    {
        if( method_exists($component, 'getHash') && $component->getHash() && $component->href == 'javascript:void(0)')
            $component->href($this->href) //so that turbolinnks are added
                ->addHash($component->getHash()) //to remove the need of repeating href for each child
                ->addClass('vl-has-hash');

        //if a link in the menu is active, then the parent is active (even if no href for the parent)
        if($component->data('active')) 
            $this->data(['active' => 'vlActive']);
    }

    public function submenu(...$args)
    {
        $this->components = $this->prepareMenu(func_get_args());

        return $this;
    }

    public function prependMenu($args)
    {
        $this->components = $this->prepareMenu(func_get_args())->concat($this->components);
        
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