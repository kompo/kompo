<?php

namespace Kompo\Forms;

use Kompo\Form;
use Kompo\Link;

class AuthLogoutForm extends Form
{
    protected $redirectTo = '/';

    protected $linkClass = 'vlColorInherit px-4 py-2';

    public function created()
    {
        $this->linkClass = $this->prop('link_class') ?: $this->linkClass;
    }

    public function handle()
    {
        \Auth::guard()->logout();
        $locale = session('kompo_locale'); //for multi-lang sites
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        session(['kompo_locale' => $locale]); //for multi-lang sites
    }

    public function render()
    {
        return [
            Link::form('Logout')->class($this->linkClass)->submit(),
        ];
    }
}
