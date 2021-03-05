<?php

namespace Kompo\Komponents\Traits;

trait FormSubmitConfigurations
{
    /**
     * Cancel default behavior of certain komponents submitting on Enter key release.
     *
     * @return self
     */
    public function dontSubmitOnEnter()
    {
        return $this->config(['noSubmitOnEnter' => true]);
    }

    /**
     * Hides the submission indicators (spinner, success, error).
     *
     * @return self
     */
    public function hideIndicators()
    {
        return $this->config(['hideIndicators' => true]);
    }

    /**
     * Reloads a new empty Form. Useful when chained after a submit() to add another item.
     *
     * @return self
     */
    public function getFreshForm()
    {
        return $this->keepModalOpen()->config([
            'getFreshForm' => true,
        ]);
    }
}
