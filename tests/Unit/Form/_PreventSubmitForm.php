<?php

namespace Kompo\Tests\Unit\Form;

class _PreventSubmitForm extends _SubmitRedirectBehaviorForm //extends the form with all config set to make sure preventSubmit takes precedence
{
    protected $preventSubmit = true;
}
