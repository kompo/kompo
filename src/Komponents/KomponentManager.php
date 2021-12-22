<?php

namespace Kompo\Komponents;

use Kompo\Core\DependencyResolver;
use Kompo\Core\KompoTarget;
use Kompo\Core\Util;

class KomponentManager
{
    /**
     * A method that gets executed at the beginning of the lifecycle.
     *
     * @param Kompo\Komponents\Komponent $komponent
     *
     * @return Komponent
     */
    public static function created($komponent, $stage)
    {
        if (config('kompo.auto_classes_for_komponents') && !$komponent->class()) {
            $komponent->class(class_basename($komponent));
        } //made this configurable

        if (method_exists($komponent, 'created')) {
            $komponent->created();
        }

        if (method_exists($komponent, $methodName = ('created'.$stage) )) {
            $komponent->{$methodName}();
        }

        static::preparePusherForFront($komponent);
    }

    /**
     * A method that gets executed at the end of the display lifecycle.
     *
     * @param Kompo\Komponents\Komponent $komponent
     *
     * @return Komponent
     */
    public static function booted($komponent)
    {
        if (method_exists($komponent, 'booted')) {
            $komponent->booted();
        }
    }

    /**
     * Prepare the Komponent's elements for display.
     *
     * @param Kompo\Komponents\Komponent $komponent
     * @param string|null              $method
     * @param bool                     $force    Force the method to run (base method for ex.)
     *
     * @return Collection
     */
    public static function prepareElementsForDisplay($komponent, $method = null, $force = false)
    {
        return static::collectFrom($komponent, $method, $force)->filter()->each(function ($component) use ($komponent) {
            $component->prepareForDisplay($komponent);

            $component->mountedHook($komponent);
        })->values()->all();
    }

    /**
     * Prepare the Komponent's elements for a backend action.
     *
     * @param Kompo\Komponents\Komponent $komponent
     * @param string|null              $method
     * @param bool                     $force    Force the method to run (base method for ex.)
     *
     * @return void
     */
    public static function prepareElementsForAction($komponent, $method = null, $force = false)
    {
        static::collectFrom($komponent, $method, $force)->filter()->each(function ($component) use ($komponent) {
            $component->prepareForAction($komponent);

            $component->mountedHook($komponent);
        }); //field elements are pushed to komponent
    }

    /**
     * Returns a collection of FIELD elements.
     *
     * @param Kompo\Komponents\Komponent $komponent
     *
     * @return Collection
     */
    public static function collectFields($komponent)
    {
        return collect($komponent->_kompo('fields'));
    }

    /**
     * Stores field elements in the Komponent for later use.
     *
     * @param Kompo\Komponents\Komponent $komponent
     * @param Kompo\Elements\Field   $field
     *
     * @return void
     */
    public static function pushField($komponent, $field)
    {
        $komponent->_kompo('fields', $field);
    }

    /**
     * Remove a field from the Komponent after use.
     *
     * @param Kompo\Komponents\Komponent $komponent
     * @param int                      $fieldKey
     *
     * @return void
     */
    public static function removeField($komponent, $fieldKey)
    {
        $komponent->_kompo('fields', $fieldKey);
    }

    /**
     * Returns a collection of elements from a method in the Komponent.
     *
     * @param Kompo\Komponents\Komponent $komponent
     * @param string|null              $method
     * @param bool                     $force    Force the method to run (base method for ex.)
     *
     * @return Collection
     */
    protected static function collectFrom($komponent, $method = null, $force = false)
    {
        return Util::collect(
            DependencyResolver::callKomponentMethod(
                $komponent,
                $method ?: KompoTarget::getDecrypted(),
                request()->all(),  //mainly for getElements
                $force
            )
        );
    }

    protected static function preparePusherForFront($komponent)
    {
        collect($komponent->pusherRefresh)->each(function ($messages, $key) use ($komponent) {
            $komponent->pusherRefresh[$key] = Util::collect($messages)->map(function ($message) {
                return class_basename($message);
            })->toArray();
        });
    }
}
