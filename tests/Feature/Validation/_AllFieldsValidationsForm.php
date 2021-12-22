<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Support\Str;
use Kompo\Core\MetaAnalysis;
use Kompo\Form;
use Kompo\Elements\Field;
use Kompo\MultiForm;
use Kompo\Tests\Utilities\_Form;

class _AllFieldsValidationsForm extends Form
{
    protected $fields;

    public function created()
    {
        $this->fields = collect(MetaAnalysis::getAllOfType(Field::class))->map(function ($field) {
            if ($field == MultiForm::class) {
                return (new $field(class_basename($field)))->formClass(_Form::class);
            }

            return new $field(class_basename($field));
        });
    }

    public function handle()
    {
    }

    public function render()
    {
        return $this->fields;
    }

    public function rules()
    {
        return collect($this->fields)->mapWithKeys(function ($field) {
            return [
                Str::snake(class_basename($field)) => 'required',
            ];
        });
    }
}
