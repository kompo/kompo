<?php

namespace Kompo\Elements;

use Kompo\Elements\Managers\LayoutManager;

abstract class Layout extends Element
{
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
            $element->{$methodName}($komponent);

            $element->mountedHook($komponent);

            //To UNCOMMENT
            //$this->prepareHashAndActiveState($element); //added this to extend Flex becoming a Menuitem
        });
    }
}