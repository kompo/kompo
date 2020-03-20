<?php 
namespace Kompo\Komponents\Traits;

trait DoesFormSubmits
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
    public function submitsForm($methodName = null)
    {
        return $this->updateDefaultTrigger(function($e) use ($methodName) {
            $e->submitsForm($methodName);
        });
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
            
            $e->submitsForm($methodName)->debounce();
            
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
            $e->submitsForm($methodName);
        });
    }

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