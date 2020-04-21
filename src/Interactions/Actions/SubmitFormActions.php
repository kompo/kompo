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
    
}