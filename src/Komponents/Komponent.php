<?php

namespace Kompo\Komponents;

use Kompo\Core\IconGenerator;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoId;
use Kompo\Elements\Element;
use Kompo\Komposers\KomposerManager;

abstract class Komponent extends Element
{
    use Traits\HasHtmlAttributes,
        Traits\UsedInTables,
        Traits\DoesTurboRefresh;
    
    public $bladeComponent = 'Komponent';

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
     * Initializes a komponent.
     *
     * @param  string $label
     * @return void
     */
    protected function vlInitialize($label)
    {
        KompoId::setForKomponent($this, $label);

        $this->label = is_null($label) ? '' : __($label);
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
    public function prepareForAction($komposer)
    {
        if($this->config('includes') && KompoAction::is('eloquent-save'))
            KomposerManager::prepareKomponentsForAction($komposer, $this->config('includes'), true);
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
     * @param  string $iconString This is the icon HTML or icon class in &lt;i class="...">&lt;/i>
     * 
     * @return Element
     */
    public function iconNonStatic($iconString)
    {
        $this->config(['icon' => IconGenerator::toHtml($iconString) ]);
        return $this;
    }

    public static function iconStatic($iconString)
    {
        return static::form('')->icon($iconString);
    }



    /**
     * Adds an icon after component's label.
     *
     * @param  string $iconString This is the icon HTML or icon class in &lt;i class="...">&lt;/i>
     * 
     * @return Element
     */
    public function rIconNonStatic($iconString)
    {
        $this->config(['rIcon' => IconGenerator::toHtml($iconString) ]);
        return $this;
    }

    public static function rIconStatic($iconString)
    {
        return static::form('')->rIcon($iconString);
    }

    /**
     * Methods that can be called both statically or non-statically
     *
     * @return array
     */
    public static function duplicateStaticMethods()
    {
        return ['label', 'icon', 'rIcon'];
    }
}