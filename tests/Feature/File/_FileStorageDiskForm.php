<?php

namespace Kompo\Tests\Feature\File;

use Kompo\File;
use Kompo\Form;
use Kompo\Tests\Models\File as FileModel;

class _FileStorageDiskForm extends Form
{
    public $model = FileModel::class;

    public function render()
    {
        return [

            File::form('File')->name('path')->disk('my-disk'),

        ];
    }
}
