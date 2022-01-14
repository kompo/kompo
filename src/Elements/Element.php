<?php

namespace Kompo\Elements;

use Kompo\Core\IconGenerator;
use Kompo\Core\KompoAction;
use Kompo\Core\KompoId;
use Kompo\Exceptions\NotAllowedLabelException;
use Kompo\Komponents\KomponentManager;

abstract class Element extends BaseElement
{
    use Traits\HasHtmlAttributes;
    use Traits\UsedInTables;
    use Traits\DoesTurboRefresh;
    use Traits\AjaxConfigurations;

    /**
     * The component's label.
     *
     * @var string
     */
    public $label;

    /**
     * A second label for when the Element has dual states.
     *
     * @var string
     */
    public $label2;

    /**
     * Constructs a Kompo\Element object.
     *
     * @param string $label
     *
     * @return void
     */
    public function __construct($label = '', $label2 = null)
    {
        $this->initialize($label);

        if ($label2) {
            $this->setLabel2($label2);
        }
    }

    /**
     * Initializes an element.
     *
     * @param string $label
     *
     * @return void
     */
    protected function initialize($label)
    {
        $this->checkIfLabelIsAllowed($label);

        KompoId::setForElement($this, $label);

        $this->label = is_null($label) ? '' : __($label);
    }

    /**
     * Sets a second label for the dual elements.
     *
     * @param  string  $label2  The secondary label
     */
    protected function setLabel2($label2 = null)
    {
        $this->checkIfLabelIsAllowed($label2);

        $this->label2 = is_null($label2) ? '' : __($label2);
    }

    protected function checkIfLabelIsAllowed($label)
    {
        if ($label && is_object($label)) {
            throw new NotAllowedLabelException($label, $this);
        }
    }

    /**
     * Passes Form attributes to the component.
     *
     * @return void
     */
    public function prepareForDisplay($komponent)
    {
    }

    /**
     * Passes Form attributes to the component.
     *
     * @return void
     */
    public function prepareForAction($komponent)
    {
        if ($this->config('includes') && KompoAction::is('eloquent-save')) { //TODO eloquent-save is wrong => check ce & others b4 changing back
            KomponentManager::prepareElementsForAction($komponent, $this->config('includes'), true);
        }
    }


    /**
     * Disables the element. Useful in _Select()->options(...) for example to disable an option.
     * 
     * @return void
     */
    public function disabled()
    {
        return $this->config([
            'disabled' => true,
        ]);
    }

    /**
     * Overwrite the initially set label.
     *
     * @param string $label
     *
     * @return Element
     */
    public function labelNonStatic($label)
    {
        $this->label = __($label);

        return $this;
    }

    /**
     * Overwrite the initially set label.
     *
     * @param string $label
     *
     * @return Element
     */
    public static function labelStatic(...$arguments)
    {
        return static::form(...$arguments);
    }

    /**
     * Adds an icon before component's label.
     *
     * @param string      $iconString     This is the icon HTML, Svg name or icon class in &lt;i class="...">&lt;/i>
     * @param string|null $iconClass      An optional class that we may assign directly to the icon
     *
     * @return Element
     */
    public function iconNonStatic($iconString, $iconClass = null)
    {        
        $this->config(['icon' => IconGenerator::toHtml($iconString, $iconClass)]);

        return $this;
    }

    public static function iconStatic($iconString, $iconClass = null)
    {
        return static::form('')->icon($iconString, $iconClass);
    }

    /**
     * Adds an icon after component's label.
     *
     * @param string $iconString This is the icon HTML or icon class in &lt;i class="...">&lt;/i>
     *
     * @return Element
     */
    public function rIconNonStatic($iconString)
    {
        $this->config(['rIcon' => IconGenerator::toHtml($iconString)]);

        return $this;
    }

    public static function rIconStatic($iconString)
    {
        return static::form('')->rIcon($iconString);
    }

    /**
     * Methods that can be called both statically or non-statically.
     *
     * @return array
     */
    public static function duplicateStaticMethods()
    {
        return ['label', 'icon', 'rIcon'];
    }
}
