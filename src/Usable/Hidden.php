<?php

namespace Kompo;

use Kompo\Elements\Field;

class Hidden extends Field
{
    public $vueComponent = 'Hidden';

    public $showHidden = false;

    protected function initialize($label)
    {
        parent::initialize($label);

        $this->name = $label; //not snakecase...
    }

    // Show for Form Demo Builder Purposes
    public function show()
    {
        $this->showHidden = true;

        return $this;
    }
}
