<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Support\Str;
use Kompo\DeleteLink;
use Kompo\Form;
use Kompo\Komponents\Field;
use Kompo\Komposers\Komposer;

class _AllFieldsValidationsForm extends Form
{
	protected $fields;

	public function created()
	{
		$dir = __DIR__.'/../../../src/Usable';

		$files = array_diff(scandir($dir), array('.', '..'));

		$this->fields = collect($files)->map(function($komponent) use($dir){
			$komponent = str_replace('.php', '', $komponent);
			$komponentClass = 'Kompo\\'.$komponent;

			if(is_dir($dir.'/'.$komponent) || is_a($komponentClass, Komposer::class, true) || is_a($komponentClass, DeleteLink::class, true))
				return null;
			
			if(($komponent = new $komponentClass($komponent)) instanceOf Field)
				return $komponent;

		})->filter();
	}

	public function handle()
	{

	}

	public function components()
	{
		return $this->fields;
	}

	public function rules()
	{
		return collect($this->fields)->mapWithKeys(function($field){
			return [
				Str::snake(class_basename($field)) => 'required'
			];
		});
	}
}