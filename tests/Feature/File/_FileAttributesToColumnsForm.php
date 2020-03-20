<?php

namespace Kompo\Tests\Feature\File;

use Kompo\File;
use Kompo\Form;
use Kompo\Tests\Models\File as FileModel;

class _FileAttributesToColumnsForm extends Form
{
	public $model = FileModel::class;

	public function components()
	{
		return [
			//When AttributesToColumns, the name of the field should correspond to the path column

			$this->store('withExtraAttributes') ? 

				File::form('File')->name('path')->attributesToColumns()->extraAttributes([
					'all_columns' => 'some-constant'
				]) : 

				File::form('File')->name('path')->attributesToColumns(),
				
			//no MultiFile - only for single File upload
		];
	}
}