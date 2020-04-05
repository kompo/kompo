<?php

namespace Kompo;

use Kompo\Textarea;

class Translatable extends Textarea
{
    public $component = 'Translatable';

    protected $locales;

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

    	$this->locales = config('kompo.locales');

        $this->data([
        	'locales' => $this->locales,
        	'currentLocale' => session('kompo_locale')
        ]);
        $this->value([]);
    }

    public function prepareValueForFront($name, $value, $komposer)
    {
        $this->value = collect($this->locales)->mapWithKeys(function($language, $locale) use($name, $komposer) {
            return [$locale => $komposer->model->getTranslation($name, $locale, false)];
        })->all();
    }

}