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
    use Actions\AddAlertActions,
        Actions\AxiosRequestActions, 
        Actions\AxiosRequestHttpActions,
        Actions\CatalogActions, 
        Actions\DebounceActions,
        Actions\EmitEventActions,
        Actions\FillPanelActions, 
        Actions\FillModalActions, 
        Actions\RedirectActions, 
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
     * The list of allowed actions that can be chained and run for each element.
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
        'emit' => [Field::class, Trigger::class, Panel::class],

        'filter' => [Field::class],
        'filterOnInput' => [Field::class],

        'inPanel' => [Field::class, Trigger::class, Panel::class],
        'inPanel1' => [Field::class, Trigger::class, Panel::class],
        'inPanel2' => [Field::class, Trigger::class, Panel::class],
        'inPanel3' => [Field::class, Trigger::class, Panel::class],
        'inPanel4' => [Field::class, Trigger::class, Panel::class],
        'inPanel5' => [Field::class, Trigger::class, Panel::class],

        'inModal' => [Field::class, Trigger::class, Panel::class],

        'alert' => [Field::class, Trigger::class, Panel::class],

        'sortCatalog' => [Field::class, Trigger::class, Panel::class],
        'refreshCatalog' => [Field::class, Trigger::class, Panel::class],

        'debounce' => [Field::class],
    ];

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
     * @return     boolean
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

    /**
     * { function_description }
     *
     * @param      <type>  $actionType  The action type
     * @param      <type>  $data        The data
     *
     * @return     self
     */
    protected function prepareAction($actionType, $data)
    {
        $this->actionType = $actionType;
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