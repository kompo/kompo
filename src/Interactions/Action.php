<?php

namespace Kompo\Interactions;

use Kompo\Elements\Traits\HasConfig;
use Kompo\Exceptions\NotFoundActionException;
use Kompo\Interactions\Traits\HasInteractions;

class Action
{
    use HasInteractions;
    use HasConfig;
    use Actions\AddAlertActions;
    use Actions\AddSlidingPanelActions;
    use Actions\AxiosRequestActions;
    use Actions\AxiosRequestHttpActions;
    use Actions\QueryActions;
    use Actions\EmitEventActions;
    use Actions\FillPanelActions;
    use Actions\FillModalActions;
    use Actions\FrontEndActions;
    use Actions\KomposerActions;
    use Actions\RedirectActions;
    use Actions\RunJsActions;
    use Actions\SubmitFormActions;

    /**
     * The type of action that will be run.
     *
     * @var string
     */
    public $actionType;

    /**
     * Information to send back to the element.
     *
     * @var array
     */
    protected $elementClosure;

    /**
     * Constructs a new Action instance.
     *
     * @param <type> $element    The element
     * @param <type> $methodName The method name
     * @param array  $parameters The parameters
     */
    public function __construct($element = null, $methodName = null, $parameters = [])
    {
        if (!$element || !$methodName) {
            return;
        }

        if (!method_exists($this, $methodName)) {
            throw new NotFoundActionException($methodName);
        }

        $this->{$methodName}(...$parameters);

        if ($this->elementClosure) {
            call_user_func($this->elementClosure, $element);
        }
    }

    /**
     * { function_description }.
     *
     * @param <type> $actionType The action type
     * @param <type> $config     The config
     *
     * @return self
     */
    protected function prepareAction($actionType, $config = null)
    {
        $this->actionType = $actionType;

        if ($config) {
            $this->config($config);
        }

        return $this;
    }

    /**
     * { function_description }.
     *
     * @param <type> $closure The closure
     *
     * @return self
     */
    protected function applyToElement($closure)
    {
        $this->elementClosure = $closure;

        return $this;
    }
}
