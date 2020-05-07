<?php

namespace Kompo;

use Kompo\Elements\Traits\HasGutters;
use Kompo\Select;

class SelectLinks extends Select
{
    use HasGutters;
    
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
     * Add a class to the container
     *
     * @param      string  $class  The class
     *
     * @return self
     */
    public function containerClass($class)
    {
    	return $this->data([
    		'containerClass' => $class
    	]);
    }

    /**
     * Add a class to the option
     *
     * @param      string  $class  The class
     *
     * @return self
     */
    public function optionClass($class)
    {
    	return $this->data([
    		'optionClass' => $class
    	]);
    }

    /**
     * Add a class to the contents of the option
     *
     * @param      string  $class  The class
     *
     * @return self
     */
    public function optionInnerClass($class)
    {
    	return $this->data([
    		'optionInnerClass' => $class
    	]);
    }
}
