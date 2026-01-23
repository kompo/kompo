<?php

namespace Kompo\Elements;

use Kompo\Core\KompoId;
use Kompo\Elements\Managers\LayoutManager;
use Kompo\Elements\Traits\HasHref;

abstract class TriggerWithSubmenu extends Trigger
{
    use HasHref;

    /**
     * Stores the child elements from the submenu.
     *
     * @var array
     */
    public $elements = [];

    /**
     * Prepares the child elements of the layout.
     *
     * @param Komponent
     */
    public function prepareForDisplay($komponent)
    {
        // Get this element's ID as parent for nested stable IDs
        $parentId = KompoId::getFromElement($this);
        $index = 0;

        collect($this->elements)->each(function ($element) use ($komponent, $parentId, &$index) {
            $element->prepareForDisplay($komponent);

            // Set stable ID for nested elements
            KompoId::setStableIdForElement($element, $parentId, $index);

            $element->mountedHook($komponent);

            $index++;
        });
    }

    /**
     * Prepares the child elements of the layout.
     *
     * @param Komponent
     */
    public function prepareForAction($komponent)
    {
        collect($this->elements)->each(function ($element) use ($komponent) {
            $element->prepareForAction($komponent);

            $element->mountedHook($komponent);
        });
    }

    protected function prepareMenu($args)
    {
        return LayoutManager::collectFilteredElements($args, $this)->each(function ($component) {
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
            $this->setActive($this->getActiveClass());
        }
    }

    //TODO document
    public function content(...$args)
    {
        $this->elements = $this->prepareMenu(func_get_args());

        return $this;
    }

    //Alias for content, will be deprecated in v4
    public function submenu(...$args)
    {
        return $this->content(...func_get_args());
    }

    public function prependMenu($args)
    {
        $this->elements = $this->prepareMenu(array_merge(func_get_args(), $this->elements));

        return $this;
    }

    /**
     * TODO document
     *
     * @return self
     */
    public function togglerClass($togglerClass)
    {
        return $this->config(['togglerClass' => $togglerClass]);
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
