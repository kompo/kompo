<?php

namespace Kompo\Elements;

use Kompo\Core\KompoId;
use Kompo\Elements\BaseElement;
use Kompo\Elements\Managers\LayoutManager;
use Kompo\Exceptions\NotAKompoBaseElementException;
use Kompo\Komponents\Traits\HasKomponentFinder;

abstract class Layout extends Element
{
    use HasKomponentFinder;
    
    /**
     * Stores the child elements of the layout.
     *
     * @var array
     */
    public $elements = [];

    /**
     * Constructs a new Layout instance.
     *
     * @param mixed ...$args The layouts elements
     */
    public function __construct(...$args)
    {
        $this->initialize(class_basename($this));

        $this->setElementsFromArguments($args);
    }


    protected function setElementsFromArguments($args)
    {

        $this->elements = LayoutManager::collectFilteredElements($args, $this)->values()->all();        
    }

    /**
     * Prepares the child elements of the layout.
     *
     * @param Komponent
     */
    public function prepareForDisplay($komponent)
    {
        $this->prepareFor('prepareForDisplay', $komponent);
    }

    /**
     * Prepares the child elements of the layout.
     *
     * @param Komponent
     */
    public function prepareForAction($komponent)
    {
        $this->prepareFor('prepareForAction', $komponent);
    }

    /**
     * Prepares the child elements of the layout.
     *
     * @var Komponent
     */
    protected function prepareFor($methodName, $komponent)
    {
        // Get this layout's ID as parent for nested stable IDs
        $parentId = KompoId::getFromElement($this);

        $index = 0;

        collect($this->elements)->each(function ($element) use ($methodName, $komponent, $parentId, &$index) {

            if (!$element instanceof BaseElement) {
                throw new NotAKompoBaseElementException($element);
            }

            $element->{$methodName}($komponent);

            // Set stable ID for nested elements during display preparation
            if ($methodName === 'prepareForDisplay') {
                KompoId::setStableIdForElement($element, $parentId, $index);
            }

            $element->mountedHook($komponent);

            $index++;
        });
    }
}
