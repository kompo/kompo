<?php

namespace Kompo\Komponents;

use Kompo\Komponents\LayoutManager;

abstract class Layout extends Komponent
{
    /**
     * Stores the child components of the layout.
     *
     * @var array
     */
    public $components = [];

    /**
     * Constructs a new Layout instance.
     *
     * @param mixed  ...$args  The layouts components
     */
    public function __construct(...$args)
    {        
        $this->vlInitialize( class_basename($this) );

        $this->components = LayoutManager::collectComponents($args, $this)->filter()->values()->all();
    }

    /**
     * Prepares the child components of the layout.
     *
     * @var Komposer
     */
    public function prepareForDisplay($komposer)
    {
        $this->prepareFor('prepareForDisplay', $komposer);
    }

    /**
     * Prepares the child components of the layout.
     *
     * @var Komposer
     */
    public function prepareForSave($komposer)
    {
        $this->prepareFor('prepareForSave', $komposer);
    }

    /**
     * Prepares the child components of the layout.
     *
     * @var Komposer
     */
    protected function prepareFor($methodName, $komposer)
    {
        if($komposer->noMargins ?? false)
            $this->noMargins();

        collect($this->components)->each(function($component) use($methodName, $komposer) {

            $component->{$methodName}($komposer);

            $component->mountedHook($komposer);

            //To UNCOMMENT
            //$this->prepareHashAndActiveState($component); //added this to extend Flex becoming a Menuitem

        });
    }
    
}