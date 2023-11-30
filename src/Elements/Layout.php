<?php

namespace Kompo\Elements;

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
        collect($this->elements)->each(function ($element) use ($methodName, $komponent) {

            if (!$element instanceof BaseElement) {
                throw new NotAKompoBaseElementException($element);
            }

            $element->{$methodName}($komponent);

            $element->mountedHook($komponent);
        });
    }
}
