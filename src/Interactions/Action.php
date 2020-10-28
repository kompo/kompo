<?php

namespace Kompo\Interactions;

use Kompo\Elements\Traits\HasData;
use Kompo\Exceptions\NotFoundActionException;
use Kompo\Interactions\Traits\HasInteractions;
use Kompo\Komponents\Field;
use Kompo\Komponents\Trigger;
use Kompo\Panel;
use Kompo\Routing\RouteFinder;

class Action
{
    use HasInteractions;
    use HasData;
    use Actions\AddAlertActions,
        Actions\AddSlidingPanelActions,
        Actions\AxiosRequestActions, 
        Actions\AxiosRequestHttpActions,
        Actions\QueryActions, 
        Actions\EmitEventActions,
        Actions\FillPanelActions, 
        Actions\FillModalActions, 
        Actions\FrontEndActions, 
        Actions\KomposerActions, 
        Actions\RedirectActions, 
        Actions\RunJsActions, 
        Actions\SubmitFormActions;

    /**
     * The type of action that will be run.
     * 
     * @var string
     */
    public $actionType;

    /**
     * Information to send back to the element.
     *
     * @var        array
     */
    protected $elementClosure;

    /**
     * Constructs a new Action instance.
     *
     * @param      <type>  $element     The element
     * @param      <type>  $methodName  The method name
     * @param      array   $parameters  The parameters
     */
    public function __construct($element = null, $methodName = null, $parameters = [])
    {
        if(!$element || !$methodName)
            return;

        if(!method_exists($this, $methodName))
            throw new NotFoundActionException($methodName);

        $this->{$methodName}(...$parameters);

        if($this->elementClosure)
            call_user_func($this->elementClosure, $element);
    }

    /**
     * { function_description }
     *
     * @param      <type>  $actionType  The action type
     * @param      <type>  $data        The data
     *
     * @return     self
     */
    protected function prepareAction($actionType, $data = null)
    {
        $this->actionType = $actionType;

        if($data)
            $this->data($data);
        
        return $this;
    }

    /**
     * { function_description }
     *
     * @param      <type>  $closure  The closure
     *
     * @return     self
     */
    protected function applyToElement($closure)
    {
        $this->elementClosure = $closure;
        return $this; 
    }
}