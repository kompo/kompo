<?php

namespace Kompo\Komponents;

use Kompo\Elements\Element;
use Kompo\Core\KompoIdCreator;

abstract class Komponent extends Element
{
    /**
     * The component's label.
     *
     * @var string
     */
    public $label;

    /**
     * Constructs a Kompo\Komponent object
     *
     * @param  string $label
     * @return void
     */
    public function __construct($label = '')
    {
        $this->vlInitialize($label);
    }

    /**
     * Another way to construct a Kompo\Komponent object
     *
     * @param  mixed $arguments
     * @return void
     */
    public static function form(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Initializes a vuravel component.
     *
     * @param  string $label
     * @return void
     */
    protected function vlInitialize($label)
    {
        KompoIdCreator::setForKomponent($this, $label);
        $this->label = $label ? __($label) : '';
    }
    
	/**
     * Passes Form attributes to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komposer)
    {

    }
    
    /**
     * Passes Form attributes to the component.
     *
     * @return void
     */
    public function prepareForSave($komposer)
    {
        if($this->data('includes')) //need to check authorize here!
            Blueprint::prepareComponentsForSave($komposer, $this->data('includes'));
    }

    /**
     * Overwrite the initially set label.
     *
     * @param  string $label
     * @return Element
     */
    public function labelNonStatic($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Overwrite the initially set label.
     *
     * @param  string $label
     * @return Element
     */
    public static function labelStatic(...$arguments)
    {
        return static::form(...$arguments);
    }

    /**
     * Adds an icon before component's label.
     *
     * @param  string $iconClass This is the class in &lt;i class="...">&lt;/i>
     * @return Element
     */
    public function iconNonStatic($iconClass)
    {
        $this->data(['icon' => $iconClass]);
        return $this;
    }

    /**
     * Adds an icon before component's label.
     *
     * @param  string $iconClass This is the class in &lt;i class="...">&lt;/i>
     * @return Element
     */
    public static function iconStatic($iconClass)
    {
        return static::form('')->icon($iconClass);
    }

    /**
     * Toggles another item identified by the $id on click if it's a Trigger, or on blur if it's a Field.
     *
     * @param      string  $id     The id of the element to be toggled.
     *
     * @return     self 
     */
    public function togglesId($id)
    {
        $this->data(['togglesId' => $id]);
        return $this;
    }



    public static function duplicateStaticMethods()
    {
        return ['label', 'icon'];
    }
}