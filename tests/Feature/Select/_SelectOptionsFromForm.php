<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\IconText;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\User;

class _SelectOptionsFromForm extends Form
{
    public $model = Obj::class;

    public function render()
    {
        return [
            //From string: relationships
            Select::form('A')->name('belongsToPlain')->optionsFrom('id', 'name'),
            Select::form('A')->name('belongsToOrdered')->optionsFrom('id', 'name'),
            Select::form('A')->name('belongsToFiltered')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('belongsToManyPlain')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('belongsToManyOrdered')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('belongsToManyFiltered')->optionsFrom('id', 'name'),
            Select::form('A')->name('morphToPlain')->optionsFrom('id', 'name', User::class),                 //Note: morphsTo need a Model defined
            Select::form('A')->name('morphToOrdered')->optionsFrom('id', 'name', User::class),               //Note: either through optionsFrom
            Select::form('A')->name('morphToFiltered')->optionsFrom('id', 'name')->morphToModel(User::class), //Note: or with morphToModel
            MultiSelect::form('A')->name('morphToManyPlain')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('morphToManyOrdered')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('morphToManyFiltered')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('morphedByManyPlain')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('morphedByManyOrdered')->optionsFrom('id', 'name'),
            MultiSelect::form('A')->name('morphedByManyFiltered')->optionsFrom('id', 'name'),

            //From Card
            Select::form('A')->name('belongsToOrdered')->optionsFrom('id', IconText::form([
                'text' => function ($model) {
                    return strtoupper($model->name);
                },
                'icon' => 'icon-location',
            ])),

            //From Array
            Select::form('A')->name('belongsToOrdered')->optionsFrom('id', [
                'text' => function ($model) {
                    return strtoupper($model->name);
                },
                'icon' => 'icon-location',
            ]),

            //From Closure
            Select::form('A')->name('belongsToOrdered')->optionsFrom('id', function ($model) {
                return strtoupper($model->name);
            }),
        ];
    }
}
