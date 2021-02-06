<?php

namespace Kompo;

use Kompo\Textarea;

class Translatable extends Textarea
{
    public $vueComponent = 'Translatable';

    protected $locales;

    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

    	$this->locales = config('kompo.locales');

        $this->config([
        	'locales' => $this->locales,
        	'currentLocale' => session('kompo_locale')
        ]);
        $this->value([]);
    }

    public function getValueFromModel($model, $name)
    {
        return collect($this->locales)->mapWithKeys(function($language, $locale) use($model) {
            return [$locale => $model->getTranslation($this->name, $locale, false)];
        })->all();
    }

}
