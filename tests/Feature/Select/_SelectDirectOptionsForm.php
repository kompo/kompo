<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\IconText;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;

class _SelectDirectOptionsForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			Select::form('A')->name('tag')->options($this->options()),
			MultiSelect::form('B')->name('tags')->options($this->options()), //from Array

			Select::form('C')->name('tag')->options(Tag::pluck('name', 'id')),
			MultiSelect::form('D')->name('tags_cast')->options(Tag::pluck('name', 'id')), //from Plucked Array

			Select::form('E')->name('tag')->options($this->optionsCard()),
			MultiSelect::form('F')->name('tags')->options($this->optionsCard()), //customLabel
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

	public function optionsCard()
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