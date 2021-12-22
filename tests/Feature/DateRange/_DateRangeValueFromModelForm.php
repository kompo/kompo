<?php

namespace Kompo\Tests\Feature\DateRange;

use Kompo\DateRange;
use Kompo\Form;
use Kompo\Tests\Models\Post;

class _DateRangeValueFromModelForm extends Form
{
    public $model = Post::class;

    public function render()
    {
        return [
            DateRange::form('DateRange')->name([
                'created_at', //just for simulation
                'updated_at', //just for simulation
            ]),
        ];
    }
}
