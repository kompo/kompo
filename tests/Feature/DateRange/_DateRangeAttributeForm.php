<?php

namespace Kompo\Tests\Feature\DateRange;

use Kompo\DateRange;
use Kompo\Form;
use Kompo\Tests\Models\Obj;

class _DateRangeAttributeForm extends Form
{
    public $model = Obj::class;

    public function komponents()
    {
        return [
            DateRange::form('A')->name([
                'start_date',
                'end_date',
            ]),
            DateRange::form('A')->name([
                'start_datetime',
                'end_datetime',
            ])->value([
                date('Y-m-d'),
                date('Y-m-d', strtotime('+1 days')),
            ]),
        ];
    }
}
