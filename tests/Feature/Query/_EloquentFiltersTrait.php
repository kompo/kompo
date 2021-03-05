<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\DateRange;
use Kompo\Input;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\User;

trait _EloquentFiltersTrait
{
    public function top()
    {
        return [
            //Attributes
            Input::form('Title')->filter(), //defaults to LIKE
            Input::form('A')->name('equal')->filter('='), //overwrite Input LIKE default
            Input::form('A')->name('greater')->filter('>='),
            Input::form('A')->name('lower')->filter('<='),
            Input::form('A')->name('like')->filter('LIKE'),
            Input::form('A')->name('startswith')->filter('STARTSWITH'),
            Input::form('A')->name('endswith')->filter('ENDSWITH'),
            DateRange::form('A')->name('between')->filter('BETWEEN'),
            MultiSelect::form('A')->name('in')->filter('IN'),
        ];
    }

    public function bottom()
    {
        return [
            //Nested relations
            Select::form('A')->name('belongsToPlain')->filter(),
            Select::form('A')->name('belongsToPlain.name')->filter('>='),
            Select::form('A')->name('belongsToOrdered.name')->filter('ENDSWITH'),
            Select::form('A')->name('belongsToFiltered.name')->filter(),
            Select::form('A')->name('belongsToPlain.posts.title')->filter('LIKE'),

            //More Relations
            MultiSelect::form()->name('belongsToManyOrdered')->filter(),

            Select::form()->name('morphToPlain')->morphToModel(User::class)->filter(),
            MultiSelect::form()->name('morphToManyOrdered')->filter(),
            MultiSelect::form()->name('morphedByManyOrdered')->filter(),

            Select::form()->name('hasOneOrdered')->filter(),
            MultiSelect::form()->name('hasManyOrdered')->filter(),

            Select::form()->name('morphOneOrdered')->filter(),
            MultiSelect::form()->name('morphManyOrdered')->filter(),
        ];
    }

    public function card($item)
    {
        return [
            'id'      => $item->id,
            'user_id' => $item->user_id,
        ];
    }
}
