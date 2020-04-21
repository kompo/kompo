<?php

namespace Kompo\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as EloqCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

class Util
{
	public static function wrap($variable)
	{
		return is_array($variable) ? $variable : [$variable];
	}

	public static function collect($variable)
	{
		if(!$variable) //when null
			return collect();

		return static::isCollection($variable) ? $variable : collect(static::wrap($variable));
	}

	public static function decode($variable)
	{
		if( (!$variable) || is_array($variable) || $variable instanceOf Model || static::isCollection($variable))
			return $variable;

		if(is_string($variable))
			return json_decode($variable, true);

		throw new RuntimeException("The variable [{$variable}] could not be decoded.");
	}

	public static function count($variable)
	{
		if(!$variable)
			return 0;

		if(is_array($variable))
			return count($variable);

		if(static::isCollection($variable))
			return $variable->count();

		throw new RuntimeException("The variable [{$variable}] could not be counted.");
	}

	public static function merge($variable, $extraAttributes)
	{
		if(!$variable || !$extraAttributes || count($extraAttributes) == 0)
			return $variable;

		if( is_array($variable) )
			return array_merge($variable, $extraAttributes);

		if( $variable instanceOf Model ){
			foreach ($extraAttributes as $key => $value) {
				$variable->{$key} = $value;
			}
			return $variable;
		}
		if(static::isCollection($variable)){
			return $variable->map(function($item) use($extraAttributes){
				return static::merge($item, $extraAttributes);
			});
		}

		throw new RuntimeException("The extra attributes could not be merged in Field.");
	}

	public static function isCollection($variable)
	{
		return $variable instanceOf Collection || $variable instanceOf EloqCollection;
	}

	public static function vueComponent($component)
	{
		return 'vl-'.Str::kebab($component->vueComponent);
	}

}