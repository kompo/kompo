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
     * @param string $methodName The class's method name that will handle the submit.
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
            'sessionTimeoutMessage' => __('sessionTimeoutMessage'),
        ]);
    }
}
