<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Tests\EnvironmentBoot;

class FieldOutputInputValueTest extends EnvironmentBoot
{
    /** @test */
    public function output_value_and_default_correct_with_model_form()
    {
        $form = _FieldOutputValueForm::boot();
        $this->assertEquals('post-title', $form->elements[0]->value);
        $this->assertEquals('obj-title', $form->elements[1]->value);
        $this->assertEquals('default-content', $form->elements[2]->value);
        $this->assertEquals('default-tag', $form->elements[3]->value);

        $this->submit($form, [
            'title'     => null,
            'obj.title' => null,
            'content'   => null,
            'obj.tag'   => null,
        ])->assertStatus(201)
        ->assertJson([
            'title'   => null,
            'content' => null,
            'obj'     => [
                'title' => null,
                'tag'   => null,
            ],
        ]);

        $form = _FieldOutputValueForm::boot(1);
        $this->assertEquals('post-title', $form->elements[0]->value);
        $this->assertEquals('obj-title', $form->elements[1]->value);
        $this->assertEquals('default-content', $form->elements[2]->value);
        $this->assertEquals('default-tag', $form->elements[3]->value);

        $this->submit($form, [
            'title'     => 'other-title',
            'obj.title' => 'other-obj-title',
            'content'   => 'other-content',
            'obj.tag'   => 'other-tag',
        ])->assertStatus(200)
        ->assertJson([
            'title'   => 'other-title',
            'content' => 'other-content',
            'obj'     => [
                'title' => 'other-obj-title',
                'tag'   => 'other-tag',
            ],
        ]);

        $form = _FieldOutputValueForm::boot(1);
        $this->assertEquals('other-title', $form->elements[0]->value);
        $this->assertEquals('other-obj-title', $form->elements[1]->value);
        $this->assertEquals('other-content', $form->elements[2]->value);
        $this->assertEquals('other-tag', $form->elements[3]->value);
    }

    /** ------------------ PRIVATE --------------------------- */
}
