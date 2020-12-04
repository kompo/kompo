<?php

namespace Kompo\Komponents;

use Kompo\Komponents\Managers\LayoutManager;

abstract class Layout extends Komponent
{
    /**
     * Stores the child komponents of the layout.
     *
     * @var array
     */
    public $komponents = [];

    /**
     * Constructs a new Layout instance.
     *
     * @param mixed  ...$args  The layouts komponents
     */
    public function __construct(...$args)
    {
        $this->vlInitialize( class_basename($this) );

        $this->komponents = LayoutManager::collectFilteredKomponents($args, $this)->values()->all();
    }

    /**
     * Prepares the child komponents of the layout.
     *
     * @param Komposer
     */
    public function prepareForDisplay($komposer)
    {
        $this->prepareFor('prepareForDisplay', $komposer);
    }

    /**
     * Prepares the child komponents of the layout.
     *
     * @param Komposer
     */
    public function prepareForAction($komposer)
    {
        $this->prepareFor('prepareForAction', $komposer);
    }

    /**
     * Prepares the child komponents of the layout.
     *
     * @var Komposer
     */
    protected function prepareFor($methodName, $komposer)
    {
        collect($this->komponents)->each(function($komponent) use($methodName, $komposer) {

            $komponent->{$methodName}($komposer);

            $komponent->mountedHook($komposer);

            //To UNCOMMENT
            //$this->prepareHashAndActiveState($komponent); //added this to extend Flex becoming a Menuitem

        });
    }
    
}