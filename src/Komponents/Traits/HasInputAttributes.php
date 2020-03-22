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
    	return $this->data([
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
        $this->data([
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
    	$this->data([
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
    	$this->data([
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
    	$this->data([
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
    	$this->data([
    		'inputPattern' => $pattern
    	]);
    	return $this;
    }
}