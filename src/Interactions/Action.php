<?php

namespace Kompo\Interactions;

use Kompo\Elements\Traits\HasData;
use Kompo\Exceptions\NotAllowedActionException;
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
    use Actions\AxiosRequestActions, 
        Actions\AxiosRequestHttpActions,
        Actions\DebounceActions,
        Actions\EmitEventActions,
        Actions\FillPanelActions, 
        Actions\FillModalActions, 
        Actions\RedirectActions, 
        Actions\RefreshCatalogActions, 
        Actions\SubmitFormActions;
    

    /**
     * { item_description }
     * 
     * @var string
     */
    public $actionType;

    /**
     * { var_description }
     *
     * @var boolean
     */
    protected $ajaxAction = false;

    /**
     * Information to send back to the element.
     *
     * @var        array
     */
    protected $elementClosure;

    /**
     * { var_description }
     *
     * @var array
     */
    protected static $allowedActionTypes = [
        'submit' => [Field::class, Trigger::class, Panel::class],
        'submitsForm' => [Field::class, Trigger::class, Panel::class], //TODO: deprecate
        'redirect' => [Field::class, Trigger::class, Panel::class],

        'get' => [Field::class, Trigger::class, Panel::class],
        'post' => [Field::class, Trigger::class, Panel::class],
        'put' => [Field::class, Trigger::class, Panel::class],
        'delete' => [Field::class, Trigger::class, Panel::class],

        'getSelf' => [Field::class, Trigger::class, Panel::class],
        'postSelf' => [Field::class, Trigger::class, Panel::class],
        'putSelf' => [Field::class, Trigger::class, Panel::class],
        'deleteSelf' => [Field::class, Trigger::class, Panel::class],

        'getKomposer' => [Field::class, Trigger::class, Panel::class],
        'getKomponents' => [Field::class, Trigger::class, Panel::class],
        'getView' => [Field::class, Trigger::class, Panel::class],

        'emitDirect' => [Field::class, Trigger::class, Panel::class],

        'inPanel' => [Field::class, Trigger::class, Panel::class],
        'inPanel1' => [Field::class, Trigger::class, Panel::class],
        'inPanel2' => [Field::class, Trigger::class, Panel::class],
        'inPanel3' => [Field::class, Trigger::class, Panel::class],
        'inPanel4' => [Field::class, Trigger::class, Panel::class],
        'inPanel5' => [Field::class, Trigger::class, Panel::class],

        'inModal' => [Field::class, Trigger::class, Panel::class],

        'sortCatalog' => [Field::class, Trigger::class, Panel::class],
        'refreshCatalog' => [Field::class, Trigger::class, Panel::class],

        'debounce' => [Field::class],
    ];

    public function __construct($element = null, $methodName = null, $parameters = [])
    {
        if(!$element || !$methodName)
            return;

        static::checkIfMethodIsAllowed($element, $methodName);

        $this->{$methodName}(...$parameters);

        if($this->elementClosure)
            call_user_func($this->elementClosure, $element);
    }

    /**
     * { function_description }
     *
     * @param      <type>             $element     The element
     * @param      <type>             $actionType  The action type
     *     *
     * @return     boolean            ( description_of_the_return_value )
     */
    public static function checkIfMethodIsAllowed($element, $actionType)
    {
        if(! ($allowedClasses = static::$allowedActionTypes[$actionType] ?? false))
            throw new NotFoundActionException($actionType);

        $elementNotAllowed = true;
        foreach ($allowedClasses as $value) {
            if($element instanceOf $value)
                $elementNotAllowed = false;
        }

        if($elementNotAllowed)
            throw new NotAllowedActionException($actionType, $element);
    }

    protected function prepareAction($actionType, $data)
    {
        //if(!$this->actionType){
            $this->actionType = $actionType;
            $this->data($data);
            return $this;
        /*}else{
            $action = new Action();
            $action->actionType = $actionType;
            $action->data($data);
            $this->onSuccess($action);
            return $action;
        }*/
    }

    protected function applyToElement($closure)
    {
        $this->elementClosure = $closure;
        return $this; 
    }
}