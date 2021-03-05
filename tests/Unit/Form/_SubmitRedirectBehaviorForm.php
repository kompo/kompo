<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _SubmitRedirectBehaviorForm extends Form
{
    protected $submitTo = 'submit-test';
    protected $submitMethod = 'PUT';
    protected $redirectTo = 'redirect-test';
    protected $redirectMessage = 'Redirecting message test...';

    public $model = Obj::class;

    public function handle()
    {
        //to make sure submitTo takes precedence
    }
}
