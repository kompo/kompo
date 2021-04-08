<?php

namespace Kompo;

use Kompo\Elements\Traits\HasGutters;
use Kompo\Komponents\Traits\HasSelectedClass;

class SelectLinks extends Select
{
    use HasGutters;
    use HasSelectedClass;

    public $vueComponent = 'SelectButtons';

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->noInputWrapper();

        $this->containerClass('vlFlex vlJustifyBetween');

        $this->optionClass('');

        $this->optionInnerClass('');
    }

    /**
     * Add a class to the container.
     *
     * @param string $class The class
     *
     * @return self
     */
    public function containerClass($class)
    {
        return $this->config([
            'containerClass' => $class,
        ]);
    }

    /**
     * Add a class to the option.
     *
     * @param string $class The class
     *
     * @return self
     */
    public function optionClass($class)
    {
        return $this->config([
            'optionClass' => $class,
        ]);
    }

    /**
     * Add a class to the contents of the option.
     *
     * @param string $class The class
     *
     * @return self
     */
    public function optionInnerClass($class)
    {
        return $this->config([
            'optionInnerClass' => $class,
        ]);
    }
}
