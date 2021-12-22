<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\DateRange;
use Kompo\Input;
use Kompo\MultiSelect;
use Kompo\Select;

trait _CollectionFiltersTrait
{
    protected $baseData = [
        'alpha',
        'bravo',
        'charlie',
        'delta',
        'echo',
        'foxtrot',
        'golf',
        'hotel',
        'india',
        'juliett',
    ];

    public function render($item, $key)
    {
        return [
            'key'   => $key,
            'value' => $item,
        ];
    }

    public function top()
    {
        return [
            Input::form('input')->filter(), //should default to LIKE
            Select::form('select')->filter(), //should default to =
            MultiSelect::form('multiselect')->filter(), //should default to IN
            Input::form('A')->name('equal')->filter('='), //overwrite Input LIKE default
            Input::form('A')->name('greater')->filter('>='),
            Input::form('A')->name('lower')->filter('<='),
            Input::form('A')->name('like')->filter('LIKE'),
            Input::form('A')->name('startswith')->filter('STARTSWITH'),
            Input::form('A')->name('endswith')->filter('ENDSWITH'),
            DateRange::form('A')->name('between')->filter('BETWEEN'),
            MultiSelect::form('A')->name('in')->filter('IN'),
            Input::form()->name('non-existing')->filter(), //non-existing in array of arrays/objects
        ];
    }
}
