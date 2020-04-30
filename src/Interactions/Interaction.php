<?php

namespace Kompo\Interactions;

use Kompo\Exceptions\NotAllowedInteractionException;
use Kompo\Exceptions\NotApplicableInteractionException;
use Kompo\Exceptions\NotFoundInteractionException;
use Kompo\Input;
use Kompo\Komponents\Field;
use Kompo\Komponents\Trigger;
use Kompo\Komposers\Komposer;
use Kompo\Panel;
use RuntimeException;

class Interaction
{
    protected static $defaultInteractions = [
        Input::class => 'input',
        Field::class => 'change',
        Trigger::class => 'click',
        Panel::class => 'load',
        Komposer::class => 'success',
        Action::class => 'success',
        ChainedAction::class => 'success'
    ];

    protected static $allowedInteractions = [
        'load' => [Panel::class, Field::class],
        'click' => [Trigger::class],
        //'focus' => [Field::class, Trigger::class],
        //'blur' => [Field::class, Trigger::class],
        //'mounted' => [Field::class, Trigger::class],
        //'keyup' => [Field::class, Trigger::class],
        //'keydown' => [Field::class, Trigger::class],
        'enter' => [Field::class], //to remove and use with keyup.enter or keydown.enter
        //'mouseover' => [Field::class, Trigger::class],
        //'mouseleave' => [Field::class, Trigger::class],
        'change' => [Field::class],
        'input' => [Field::class],
        'success' => ['*'],
        'error' => ['*']
    ];

    public $interactionType;
    public $action;

    /**
     * Constructs a new instance.
     *
     * @param      <type>   $element          The element
     * @param      boolean  $interactionType  The interaction type
     */
    public function __construct($element, $interactionType = null)
    {
        $this->interactionType = $interactionType ?: static::getDefaultInteraction($element);
    }

    /**
     * { function_description }
     *
     * @param      <type>                                            $element          The element
     * @param      <type>                                            $interactionType  The interaction type
     *
     * @throws     \Kompo\Exceptions\NotAllowedInteractionException  (description)
     * @throws     \Kompo\Exceptions\NotFoundInteractionException    (description)
     */
    public static function checkIfAcceptable($element, $interactionType)
    {
        if(! ($allowedClasses = static::$allowedInteractions[$interactionType] ?? false))
            throw new NotFoundInteractionException($interactionType);

        $elementNotAllowed = true;
        foreach ($allowedClasses as $value) {
            if($value === '*' || $element instanceOf $value)
                $elementNotAllowed = false;
        }

        if($elementNotAllowed)
            throw new NotAllowedInteractionException($interactionType, $element);
    }

    public static function placeInElement($activeElement, $action, $interactionType)
    {
        if(in_array($interactionType, ['success', 'error'])){

            if(!static::getLastInteraction($activeElement) && !($activeElement instanceOf Action))
                throw new NotApplicableInteractionException($interactionType, $activeElement);
                
            static::addToLastNested($activeElement, $action, $interactionType);
        }else{
            static::appendToWithAction($activeElement, $action, $interactionType);
        }
    }

    /**
     * Appends to with action.
     *
     * @param      <type>  $element          The element
     * @param      <type>  $action           The action
     * @param      <type>  $interactionType  The interaction type
     */
    public static function appendToWithAction($element, $action, $interactionType = null)
    {
        $interaction = new static($element, $interactionType);
        $interaction->action = $action;

        $element->interactions[] = $interaction; 
    }

    /**
     * Adds to last nested.
     *
     * @param      <type>  $element          The element
     * @param      <type>  $action           The action
     * @param      <type>  $interactionType  The interaction type
     */
    public static function addToLastNested($element, $action, $interactionType = null)
    {
        $lastInteraction = static::getLastInteraction($element);

        if(!$lastInteraction){

            static::appendToWithAction($element, $action, $interactionType);

        }else{

            if(in_array($lastInteraction->interactionType, ['success', 'error'])){
                static::appendToWithAction($element, $action, $interactionType);
            }else{
                static::addToLastNested($lastInteraction->action, $action, $interactionType);
            }

        }
    }

    protected static function getLastInteraction($element)
    {
        return $element->interactions[count($element->interactions) - 1] ?? null;
    }

    /**
     * Gets the default interaction.
     *
     * @param      <type>  $element  The element
     *
     * @return     <type>  The default interaction.
     */
    protected static function getDefaultInteraction($element)
    {
        return collect(static::$defaultInteractions)->first(function($trigger, $class) use($element) {
            if($element instanceOf $class)
                return $trigger;
        });
    }
}