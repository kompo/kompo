<?php

namespace Kompo;

class Translatable extends Textarea
{
    public $vueComponent = 'Translatable';

    protected $locales;

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->locales = config('kompo.locales');

        $this->config([
            'locales'       => $this->locales,
            'currentLocale' => session('kompo_locale'),
        ]);
        $this->value([]);
    }

    public function getValueFromModel($model, $name)
    {
        return collect($this->locales)->mapWithKeys(function ($language, $locale) use ($model, $name) {
            return [$locale => $model->getTranslation($name, $locale, false)];
        })->all();
    }

    //TODO document
    public function asTextarea()
    {
        return $this->config([
            'asTextarea' => true,
        ]);
    }
}
