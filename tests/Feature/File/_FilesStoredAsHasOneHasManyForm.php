<?php

namespace Kompo\Tests\Feature\File;

use Kompo\File;
use Kompo\Form;
use Kompo\MultiFile;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Utilities\SwitchableFormTrait;

class _FilesStoredAsHasOneHasManyForm extends Form
{
    use SwitchableFormTrait;

    public $model = Obj::class;

    public function render()
    {
        return $this->filter([
            File::form('A')->name('hasOnePlain'),
            File::form('A')->name('hasOneOrdered'),
            File::form('A')->name('hasOneFiltered')->extraAttributes(['order' => 1]),
            MultiFile::form('A')->name('hasManyPlain'),
            MultiFile::form('A')->name('hasManyOrdered'),
            MultiFile::form('A')->name('hasManyFiltered')->extraAttributes(['order' => 1]),
        ]);
    }
}
