<?php

namespace Kompo\Komposers;

use Kompo\Exceptions\ComponentsMethodNotFoundException;
use Kompo\Core\Util;

class KomposerManager extends Komposer
{
    /**
     * A method that gets executed at the beginning of the lifecycle.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * 
     * @return Komposer
     */
	public static function created($komposer)
    {
    	if(config('kompo.auto_classes_for_komposers'))
        	$komposer->class($komposer->class ?: class_basename($komposer)); //made this configurable

		if(method_exists($komposer, 'created'))
			$komposer->created();

    	return $komposer;
    }

    /**
     * Prepare the Komposer's components for being displayed.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string $method
     *
     * @return Collection
     */
    public static function prepareComponentsForDisplay($komposer, $method)
    {
        return static::collectFrom($komposer, $method)->filter()->each( function($component) use ($komposer) {

            $component->prepareForDisplay($komposer);

            $component->mountedHook($komposer);

        })->values()->all();
    }

    /**
     * Prepare the Komposer's components for a backend action.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string $method
     *
     * @return void
     */
    public static function prepareComponentsForAction($komposer, $method)
    {
        static::collectFrom($komposer, $method)->filter()->each( function($component) use ($komposer) {

            $component->prepareForAction($komposer);

            $component->mountedHook($komposer);

        }); //components are pushed in fields
    }

    /**
     * Returns a collection of FIELD components
     *
     * @param Kompo\Komposers\Komposer $komposer
     *
     * @return Collection
     */
    public static function collectFields($komposer)
    {
    	return collect($komposer->_kompo('fields'));
    }

    /**
     * Stores field components in the Komposer for later use
     *
     * @param Kompo\Komposers\Komposer $komposer
     * @param Kompo\Komponents\Field   $field
     * 
     * @return void
     */
    public static function pushField($komposer, $field)
    {
    	$komposer->_kompo('fields', $field);
    }

    /**
     * Returns a collection of components from a method in the Komposer.
     *
     * @param Kompo\Komposers\Komposer $komposer
     * @param string $method
     *
     * @throws \Kompo\Exceptions\ComponentsMethodNotFoundException
     *
     * @return Collection
     */
    protected static function collectFrom($komposer, $method)
    {
        if($method && !method_exists($komposer, $method))
            throw new ComponentsMethodNotFoundException($method, class_basename($komposer));

        return Util::collect($komposer->{$method}());
    }
}