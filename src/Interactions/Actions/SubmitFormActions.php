<?php

namespace Kompo\Interactions\Actions;

use Kompo\Core\KompoTarget;

trait SubmitFormActions
{
    /**
     * Submits the form. By default, it will submit to the handle method. The default trigger is:
     * On click for Buttons/Links.
     * On change for fields (after blur).
     *
     * @param null|string $methodName If custom submit handling: The class's method that will handle the submit.
     *
     * @return self
     */
    public function submit($methodName = null)
    {
        $this->applyToElement(function ($el) {
            $el->config([
                'submitsForm' => true,
            ]);
        });

        return $this->prepareAction('submitForm', [
            'kompoMethod'           => $methodName ? KompoTarget::getEncrypted($methodName) : null,
        ]);
    }

    /**
     * { function_description }
     *
     * @param      array  $payload     The payload
     * @param      null|string    $methodName  The method name
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function submitWith($payload, $methodName = null)
    {
        return $this->submit($methodName)->config([
            'submitPayload' => $payload,
        ]);
    }
}
