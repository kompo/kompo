<?php

namespace Kompo\Komponents;

class KomponentFinder
{
    /**
     * The default element to start the search from.
     * @var BaseElement
     */
    protected $defaultEl;

    /**
     * The component to search for.
     * @var string
     */
    protected $component;

    /**
     * The id to search for.
     * @var string
     */
    protected $id;

    /**
     * The class to search for.
     * @var string
     */
    protected $class;

    /**
     * The string to search for in the label.
     * @var string
     */
    protected $stringContains;


    public function __construct($el = null)
    {
        $this->defaultEl = $el;
    }

    /* SEARCH SETTERS */
    public function byStringContains($stringContains)
    {
        $this->stringContains = $stringContains;

        return $this;
    }
    
    public function byComponent($component)
    {
        $this->component = $component;

        return $this;
    }

    public function byId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function byClass($class)
    {
        $this->class = $class;

        return $this;
    }
    /* END SEARCH SETTERS */

    /**
     * Checks if the element matches the search criteria.
     * @param  BaseElement $el The element to check.
     */
    protected function isCorrectComponent($el)
    {
        return (!$this->component || class_basename($el) == class_basename($this->component))
         && (!$this->id || $el->id == $this->id)
         && (!$this->class || property_exists($el, 'class') && ($el->class == $this->class || in_array($this->class, explode(' ', $el->class))))
         && (!$this->stringContains || str_contains($el->label, $this->stringContains));
    }

    /**
     * Depending on the type of element, returns the elements to iterate through.
     * @param  BaseElement $el The element to iterate through.
     */
    protected function getIterateElements($el)
    {
        if(property_exists($el, 'query')) {
            if (!count($el->query->items())) {
                return false;
            }

            return collect($el->query->items())->map(fn($el) => $el['render']);
        }

        if (!property_exists($el, 'elements') || !$el->elements || !count($el->elements)) {
            return false;
        }

        return $el->elements;
    }

    /* PUBLIC FINDER METHODS */

    /**
     *  Finds the first element that matches with the setted search criteria.
     *  @param ?BaseElement $el The element to start the search from. If null, the default element is used.
     */
    public function find($el = null)
    {
        $el = $el ?? $this->defaultEl;

        if($this->isCorrectComponent($el)) return $el;

        $elements = $this->getIterateElements($el);

        if(!$elements) return false;

        foreach ($elements as $element) {
            $resEl = $this->find($element);

            if ($resEl) {
                $resEl->parent = $el;
                return $resEl;
            }
        }

        return false;
    }

    /**
     * Count all the elements that match with the setted search criteria.
     * @param ?BaseElement $el The element to start the search from. If null, the default element is used.
     */
    public function count($el = null)
    {
        $el = $el ?? $this->defaultEl;
        
        $count = 0;

        if($this->isCorrectComponent($el)) $count = 1;

        $elements = $this->getIterateElements($el);

        if(!$elements) return $count;

        foreach ($elements as $element) {
            $count += $this->count($element);
        }

        return $count;
    }
    /* END PUBLIC FINDER METHODS */
}