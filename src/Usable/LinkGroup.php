<?php

namespace Kompo;

use Kompo\Elements\Traits\HasGutters;
use Kompo\Elements\Traits\HasSelectedClass;

class LinkGroup extends Select
{
    use HasGutters;
    use HasSelectedClass;

    public $vueComponent = 'LinkGroup';

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->configureButtonGroup();
    }

    protected function configureButtonGroup()
    {
        $this->noInputWrapper();

        $this->containerClass('flex justify-between');

        $this->optionClass('cursor-pointer');
    }

    //TODO document
    public function vertical()
    {
        $this->containerClass('flex flex-col');
        return $this;
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
