<?php 

namespace Kompo\Komponents\Traits;

trait FormSubmitConfigurations
{
    /**
     * Cancel default behavior of certain components submitting on Enter key release.
     *
     * @return self
     */
    public function dontSubmitOnEnter()
    {
        return $this->data(['noSubmitOnEnter' => true]);
    }

    /**
     * Hides the submission indicators (spinner, success, error).
     *
     * @return self
     */
    public function hideIndicators()
    {
        return $this->data(['hideIndicators' => true]);
    }

}