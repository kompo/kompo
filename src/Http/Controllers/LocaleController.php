<?php

namespace Kompo\Http\Controllers;

class LocaleController
{
    public function __invoke($locale = 'en')
    {
        if (!array_key_exists($locale, config('kompo.locales'))) {
            $locale = config('app.locale');
        }

        \Cookie::queue('locale', $locale);

        return redirect()->back();
    }
}
