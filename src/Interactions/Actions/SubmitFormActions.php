<?php

namespace Kompo\Interactions\Actions;

trait SubmitFormActions
{
    /**
     * Submits the form. By default, it will submit to the handle method. The default trigger is:
     * On click for Buttons/Links.
     * On change for fields (after blur).
     *
     * @param  string  $methodName    The class's method name that will handle the submit.
     * 
     * @return self
     */
    public function submit($methodName = null)
    {
        return $this->prepareAction('submitForm', [
            'kompoMethod' => $methodName
        ]);
    }

    /**
     * Submits the form. By default, it will submit to the handle method. The default trigger is:
     * On click for Buttons/Links.
     * On change for fields (after blur).
     *
     * @param  string  $methodName    The class's method name that will handle the submit.
     * 
     * @return self
     */
    public function submitsForm($methodName = null) //same as above TODO: deprecate
    {
        return $this->prepareAction('submitForm', [
            'kompoMethod' => $methodName
        ]);
    }

    /**
     * Submits the form when a user types in a field. By default, the request is debounced by 500ms.
     * 
     * @param  string  $methodName    The class's method name that will handle the submit.
     * 
     * @return self
     */
    public function submitsOnInput($methodName = null)
    {
        return $this->onInput(function($e) use ($methodName) {
            
            $e->submit($methodName)->debounce();
            
        });
    }

    /**
     * Submits the form when the ENTER key is released.
     *
     * @param  string  $methodName    The class's method name that will handle the submit.
     * 
     * @return self
     */
    public function submitsOnEnter($methodName = null)
    {
        return $this->onEnter(function($e) use ($methodName) {
            $e->submit($methodName);
        });
    }
    
}