<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\IconText;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;

class _SelectAttributeFillsForm extends Form
{
    public $model = Obj::class;

    public function komponents()
    {
        $optionsMethod = 'options'.$this->store('optionsMethod');

        return [
            Select::form('A')->name('tag')->options($this->{$optionsMethod}()),
            MultiSelect::form('B')->name('tags')->options($this->{$optionsMethod}()),
            MultiSelect::form('C')->name('tags_cast')->options($this->{$optionsMethod}()),
        ];
    }

    public function options()
    {
        return [
            '1' => 'Option 1',
            '2' => 'Option 2',
            '3' => 'Option 3',
            '4' => 'Option 4',
            '5' => 'Option 5',
        ];
    }

    public function optionsTags()
    {
        return Tag::pluck('name', 'id');
    }

    public function optionsCards()
    {
        return [
            '1' => IconText::form(['icon' => 'icon-location', 'text' => 'Option 1']),
            '2' => IconText::form(['icon' => 'icon-location', 'text' => 'Option 2']),
            '3' => IconText::form(['icon' => 'icon-location', 'text' => 'Option 3']),
            '4' => IconText::form(['icon' => 'icon-location', 'text' => 'Option 4']),
            '5' => IconText::form(['icon' => 'icon-location', 'text' => 'Option 5']),
        ];
    }
}
