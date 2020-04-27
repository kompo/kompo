<?php

namespace Kompo\Komposers;

use Kompo\Core\DependencyResolver;
use Kompo\Core\Util;

class KomposerManager
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
     * Prepare the Komposer's komponents for display.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string|null $method
     *
     * @return Collection
     */
    public static function prepareKomponentsForDisplay($komposer, $method = null)
    {
        return static::collectFrom($komposer, $method)->filter()->each( function($component) use ($komposer) {

            $component->prepareForDisplay($komposer);

            $component->mountedHook($komposer);

        })->values()->all();
    }

    /**
     * Prepare the Komposer's komponents for a backend action.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string|null $method
     *
     * @return void
     */
    public static function prepareKomponentsForAction($komposer, $method = null)
    {
        static::collectFrom($komposer, $method)->filter()->each( function($component) use ($komposer) {

            $component->prepareForAction($komposer);

            $component->mountedHook($komposer);

        }); //field komponents are pushed to komposer
    }

    /**
     * Returns a collection of FIELD komponents
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
     * Stores field komponents in the Komposer for later use
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
     * Remove a field from the Komposer after use
     *
     * @param Kompo\Komposers\Komposer $komposer
     * @param integer   $fieldKey
     * 
     * @return void
     */
    public static function removeField($komposer, $fieldKey)
    {
        $komposer->_kompo('fields', $fieldKey);
    }

    /**
     * Returns a collection of komponents from a method in the Komposer.
     *
     * @param Kompo\Komposers\Komposer $komposer
     * @param string|null              $method
     *
     * @return Collection
     */
    protected static function collectFrom($komposer, $method = null)
    {
        return Util::collect(
            $method ? 
                
                $komposer->{$method}() : //for 'komponents', 'top', 'right', 'left', 'bottom'.
                
                DependencyResolver::callKomposerMethod($komposer, null, request()->all()) //mainly for getKomponents
        );
    }
}