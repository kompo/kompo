<?php 

namespace Kompo\Komponents\Traits;

trait HasInputAttributes
{
    /**
     * Sets the type attribute of the input. Ex: 'text', 'number', 'password', ... 
     * The default is 'text'.
     *
     * @param      string  $type   The HTML input type attribute
     *
     * @return self  
     */
    public function type($type = 'text') //user-shortcut only
    {
        return $this->inputType($type);
    }

    //For internal use
    public function inputType($type = 'text')
    {
    	return $this->config([
    		'inputType' => $type
    	]);
    }

    /**
     * Sets the maxlength attribute of the input.
     *
     * @param      number  $maxlength   The HTML input maxlength attribute
     *
     * @return self  
     */
    public function maxlength($maxlength)
    {
        $this->config([
            'inputMaxlength' => $maxlength
        ]);
        return $this;
    }

    /**
     * Sets the min attribute of the input.
     *
     * @param      number  $min   The HTML input min attribute
     *
     * @return self  
     */
    public function min($min)
    {
    	$this->config([
    		'inputMin' => $min
    	]);
    	return $this;
    }

    /**
     * Sets the max attribute of the input.
     *
     * @param      number  $max   The HTML input max attribute
     *
     * @return self  
     */
    public function max($max)
    {
    	$this->config([
    		'inputMax' => $max
    	]);
    	return $this;
    }

    /**
     * Sets the step attribute of the input.
     *
     * @param      number  $step   The HTML input step attribute
     *
     * @return self  
     */
    public function step($step)
    {
    	$this->config([
    		'inputStep' => $step
    	]);
    	return $this;
    }

    /**
     * Sets the pattern attribute of the input.
     *
     * @param      string  $pattern   The HTML input pattern attribute
     *
     * @return self  
     */
    public function pattern($pattern)
    {
    	$this->config([
    		'inputPattern' => $pattern
    	]);
    	return $this;
    }
}