<?php

namespace Kompo\Tests\Feature\File;

use Kompo\File;
use Kompo\Form;
use Kompo\MultiFile;
use Kompo\Tests\Models\Obj;

class _FilesStoredAsMorphOneMorphManyForm extends Form
{
	public $model = Obj::class;

	public function components()
	{
		return [
			File::form('A')->name('morphOnePlain'),
			File::form('A')->name('morphOneOrdered'),
			File::form('A')->name('morphOneFiltered')->extraAttributes(['order' => 1]),
			MultiFile::form('A')->name('morphManyPlain'),
			MultiFile::form('A')->name('morphManyOrdered'),
			MultiFile::form('A')->name('morphManyFiltered')->extraAttributes(['order' => 1])
		];
	}
}