<?php

namespace Kompo\Tests\Feature\Routing;

use Kompo\Form;
use Kompo\Input;
use Kompo\Tests\Models\Post;

class _RouteParametersForm extends Form
{
    use _RouteParametersCommonTrait;

    public $model = Post::class; //otherwise modelKey is not set

    public $modelKey; //Had to expose it to see it in a route response

    public function created()
    {
        $this->modelKey = $this->modelKey();
        $this->commonCreated();
    }

    public function komponents()
    {
        return Input::form('Form-is-rendered');
    }
}
