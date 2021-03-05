<?php

namespace Kompo\Tests\Feature\Authorization;

use Kompo\Form;

class _SelfRequestUnauthorizedForm extends Form
{
    public function authorize()
    {
        return false;
    }
}
