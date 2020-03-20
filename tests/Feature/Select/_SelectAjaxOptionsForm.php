<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Form;
use Kompo\MultiSelect;
use Kompo\Select;
use Kompo\SubmitButton;
use Kompo\Tests\Models\Post;

class _SelectAjaxOptionsForm extends Form
{
	public $model = Post::class;
	
	public function authorize()
	{
		return true;
	}

	public function components()
	{
		return [
			Select::form('tag')->searchOptions(1),
			MultiSelect::form('tags')->searchOptions(1),
			SubmitButton::form('Submit')
		];
	}

	public function searchOptions($search, $searchByValue = false)
	{
		if(!$searchByValue){
			return collect($this->options())->filter(function($opt) use($search){
				return strpos($opt, $search) !== false;
			});
		}else{
			return collect($this->options())->filter(function($opt, $key) use($search){
				return $search == $key;
			});
		}
	}

	public function searchTag($search, $searchByValue = false)
	{
		return $this->searchOptions($search, $searchByValue);
	}

	public function searchTags($search, $searchByValue = false)
	{
		return $this->searchOptions($search, $searchByValue);
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

	public function rules()
	{
		return [
			'tag' => 'nullable|in:1,2,3,4,5',
			'tags' => 'min:1',
			'tags.*' => 'in:1,2,3,4,5',
    	];
	}
}