<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;

class FormInstantiationTest extends EnvironmentBoot
{
    /** @test */
    public function form_is_called_with_all_permutations_of_id_and_store()
    {
        $obj = factory(Obj::class)->create();

        $form = new _FormInstantiationForm();

        $this->assertNull($form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(0, $form->store);

        $form = new _FormInstantiationForm(1);

        $this->assertEquals(1, $form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(0, $form->store);

        $form = new _FormInstantiationForm(1, ['some-key' => 'some-value']);

        $this->assertEquals(1, $form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(1, $form->store);
        $this->assertEquals('some-value', $form->store['some-key']);

        $form = new _FormInstantiationForm(null, ['some-key' => 'some-value']);

        $this->assertNull($form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(1, $form->store);
        $this->assertEquals('some-value', $form->store['some-key']);

        $form = new _FormInstantiationForm(['some-key' => 'some-value']);

        $this->assertNull($form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(1, $form->store);
        $this->assertEquals('some-value', $form->store['some-key']);

        $form = new _FormInstantiationForm(['some-key' => 'some-value'], 1);

        $this->assertEquals(1, $form->modelId);
        $this->assertInstanceOf(Obj::class, $form->model);
        $this->assertCount(1, $form->store);
        $this->assertEquals('some-value', $form->store['some-key']);
    }
}
