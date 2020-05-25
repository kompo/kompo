<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Support\Str;
use Kompo\Form;
use Kompo\Komponents\Field;

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

			if(is_a($komponentClass, Field::class, true))
				return new $komponentClass($komponent);

		})->filter();
	}

	public function handle()
	{

	}

	public function komponents()
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