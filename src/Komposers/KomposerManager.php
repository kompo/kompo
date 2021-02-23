<?php

namespace Kompo\Komposers;

use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoTarget;
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
    	if(config('kompo.auto_classes_for_komposers') && !$komposer->class())
        	$komposer->class(class_basename($komposer)); //made this configurable

		if(method_exists($komposer, 'created'))
			$komposer->created();

        static::preparePusherForFront($komposer);
    }

    /**
     * A method that gets executed at the end of the display lifecycle.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * 
     * @return Komposer
     */
    public static function booted($komposer)
    {        
        if(method_exists($komposer, 'booted'))
            $komposer->booted();
    }

    /**
     * Prepare the Komposer's komponents for display.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string|null $method
     * @param boolean $force                     Force the method to run (base method for ex.)
     *
     * @return Collection
     */
    public static function prepareKomponentsForDisplay($komposer, $method = null, $force = false)
    {
        return static::collectFrom($komposer, $method, $force)->filter()->each( function($component) use ($komposer) {

            $component->prepareForDisplay($komposer);

            $component->mountedHook($komposer);

        })->values()->all();
    }

    /**
     * Prepare the Komposer's komponents for a backend action.
     * 
     * @param Kompo\Komposers\Komposer $komposer
     * @param string|null $method
     * @param boolean $force                     Force the method to run (base method for ex.)
     *
     * @return void
     */
    public static function prepareKomponentsForAction($komposer, $method = null, $force = false)
    {
        static::collectFrom($komposer, $method, $force)->filter()->each( function($component) use ($komposer) {

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
     * @param boolean $force                     Force the method to run (base method for ex.)
     *
     * @return Collection
     */
    protected static function collectFrom($komposer, $method = null, $force = false)
    {
        return Util::collect(
            DependencyResolver::callKomposerMethod(
                $komposer, 
                $method ?: KompoTarget::getDecrypted(), 
                request()->all(),  //mainly for getKomponents
                $force
            )
        );
    }

    protected static function preparePusherForFront($komposer)
    {
        collect($komposer->pusherRefresh)->each(function($messages, $key) use($komposer) {

            $komposer->pusherRefresh[$key] = Util::collect($messages)->map(function($message){

                return class_basename($message);

            })->toArray();

        });
    }
}