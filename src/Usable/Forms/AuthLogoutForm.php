<?php
namespace Kompo\Forms;

use Kompo\Form;
use Kompo\Link;

class AuthLogoutForm extends Form
{
    protected $redirectTo = '/';

	public function handle($request)
    {
        \Auth::guard()->logout();
        $locale = session('kompo_locale'); //for multi-lang sites
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session( ['kompo_locale' => $locale] ); //for multi-lang sites
    }

	public function components()
	{
		return [
			Link::form('Logout')->submitsForm()
		];
	}

}