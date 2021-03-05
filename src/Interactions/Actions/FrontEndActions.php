<?php

namespace Kompo\Interactions\Actions;

trait FrontEndActions
{
    /**
     * Toggles another item identified by the $id. By default, the target item is hidden on load.
     *
     * @param string    $id           The id of the element to be toggled.
     * @param bool|null $toggleOnLoad Whether the item should be toggled on inital load. Default is true.
     *
     * @return self
     */
    public function toggleId($id, $toggleOnLoad = true)
    {
        $this->applyToElement(function ($el) use ($id, $toggleOnLoad) {
            $el->config([
                'toggleId'     => $id,
                'toggleOnLoad' => $toggleOnLoad,
            ]);
        });

        return $this->prepareAction('toggleElement', [
            'toggleId' => $id,
        ]);
    }

    /**
     * The Komponent will hide itself after an interaction.
     *
     * @return self
     */
    public function hideSelf()
    {
        return $this->prepareAction('hideSelf');
    }

    //TODO: document
    public function scrollTo($selector, $duration, $options = [], $timeoutDuration = 500)
    {
        return $this->prepareAction('scrollTo', [
            'scrollSelector'  => $selector,
            'scrollDuration'  => $duration,
            'scrollOptions'   => $options,
            'timeoutDuration' => $timeoutDuration,
        ]);
    }

    //TODO: document
    public function toggleClass($class)
    {
        return $this->prepareAction('toggleClass', [
            'toggleClass' => $class,
        ]);
    }

    //TODO: document
    public function addClass($class)
    {
        return $this->prepareAction('addClass', [
            'addClass' => $class,
        ]);
    }

    //TODO: document
    public function removeClass($class)
    {
        return $this->prepareAction('removeClass', [
            'removeClass' => $class,
        ]);
    }

    //TODO: document
    public function activate()
    {
        return $this->emitDirect('activate');
    }

    //TODO: document
    public function removeSelf()
    {
        return $this->prepareAction('removeSelf');
    }
}
