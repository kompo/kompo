<?php

namespace Kompo\Komposers;

class KomposerManager extends Komposer
{
	public static function created($komposer)
    {
    	if(config('kompo.auto_classes_for_komposers'))
        	$komposer->class($komposer->class ?: class_basename($komposer)); //made this configurable

		if(method_exists($komposer, 'created'))
			$komposer->created();

    	return $komposer;
    }
}