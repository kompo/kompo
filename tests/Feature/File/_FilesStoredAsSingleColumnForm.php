<?php

namespace Kompo\Tests\Feature\File;

use Kompo\File;
use Kompo\Form;
use Kompo\MultiFile;
use Kompo\Tests\Models\Obj;

class _FilesStoredAsSingleColumnForm extends Form
{
    public $model = Obj::class;

    public function render()
    {
        return [
            File::form('A')->name('file'),
            File::form('A')->name('file_cast'),
            MultiFile::form('A')->name('files'),
            MultiFile::form('A')->name('files_cast'),
        ];
    }
}
