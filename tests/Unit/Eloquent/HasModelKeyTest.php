<?php
namespace Kompo\Tests\Unit\Eloquent;

use Kompo\Tests\EnvironmentBoot;

class HasModelKeyTest extends EnvironmentBoot
{
    /** @test */
	public function model_key_is_null_in_constructor()
	{
		$form = _Form();
		$this->assertNull($form->modelKey());
	}

    /** @test */
	public function model_key_is_set_in_constructor()
	{
		$form = new _PostForm(6);
		$this->assertEquals(6, $form->modelKey());

		$form = new _PostForm('string-id');
		$this->assertEquals('string-id', $form->modelKey());
	}

    /** @test */
	public function model_key_is_set_with_method()
	{
		$form = new _PostForm(6);
		$this->assertEquals(6, $form->modelKey());

		$form->modelKey(7);
		$this->assertEquals(7, $form->modelKey());
	}
}