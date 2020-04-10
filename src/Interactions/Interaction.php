<?php

namespace Kompo\Interactions;

use Kompo\Exceptions\NotAllowedInteractionException;
use Kompo\Exceptions\NotFoundInteractionException;
use Kompo\Form;
use Kompo\Komponents\Field;
use Kompo\Komponents\Layout;
use Kompo\Komponents\Trigger;
use RuntimeException;

class Interaction
{
    protected static $defaultInteractions = [
        Field::class => 'change',
        Trigger::class => 'click',
        Form::class => 'success',
        Action::class => 'success'
    ];

    protected static $allowedInteractions = [
        'load' => [Layout::class], //TODO: ex: Panel
        'click' => [Field::class, Trigger::class],
        //'focus' => [Field::class, Trigger::class],
        //'blur' => [Field::class, Trigger::class],
        //'mounted' => [Field::class, Trigger::class],
        //'keyup' => [Field::class, Trigger::class],
        //'keydown' => [Field::class, Trigger::class],
        //'enter' => [Field::class, Trigger::class], //to remove and use with keyup.enter or keydown.enter
        //'mouseover' => [Field::class, Trigger::class],
        //'mouseleave' => [Field::class, Trigger::class],
        'change' => [Field::class],
        'input' => [Field::class],
        'success' => [Action::class],
        'error' => [Action::class]
    ];

    public $interactionType;
    public $action;

    public function __construct($element, $interactionType = null)
    {
        $this->interactionType = $interactionType ?: static::getDefaultInteraction($element);

        if(! ($allowedClasses = static::$allowedInteractions[$this->interactionType] ?? false))
            throw new NotFoundInteractionException($this->interactionType);

        $elementNotAllowed = true;
        foreach ($allowedClasses as $value) {
            if($element instanceOf $value)
                $elementNotAllowed = false;
        }

        if($elementNotAllowed)
            throw new NotAllowedInteractionException($this->interactionType, $element);

    }

    public static function appendToWithAction($element, $action, $interactionType = null)
    {
        $interaction = new static($element, $interactionType);
        $interaction->action = $action;

        $element->interactions[] = $interaction; 
    }

    public static function addToLastNested($element, $action, $interactionType = null)
    {
        $lastInteraction = $element->interactions[count($element->interactions) - 1] ?? null;

        if(!$lastInteraction){

            static::appendToWithAction($element, $action, $interactionType);

        }else{

            static::addToLastNested($lastInteraction->action, $action);

        }
    }

    protected static function getDefaultInteraction($element)
    {
        return collect(static::$defaultInteractions)->first(function($trigger, $class) use($element) {
            if($element instanceOf $class)
                return $trigger;
        });
    }
}