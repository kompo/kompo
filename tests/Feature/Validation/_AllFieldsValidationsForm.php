<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Support\Str;
use Kompo\Form;
use Kompo\KompoServiceProvider;
use Kompo\Komponents\Field;
use Kompo\Komposers\Komposer;
use Kompo\Model;

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

			if($this->excludedFiles($dir, $komponent, $komponentClass))
				return null;
			
			if(($komponent = new $komponentClass($komponent)) instanceOf Field)
				return $komponent;

		})->filter();
	}

	public function handle()
	{

	}

	public function komponents()
	{
		return $this->fields;
	}

	public function excludedFiles($dir, $komponent, $komponentClass)
	{
		return is_dir($dir.'/'.$komponent) || is_a($komponentClass, Komposer::class, true) 
			|| is_a($komponentClass, Model::class, true) || is_a($komponentClass, KompoServiceProvider::class, true);
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