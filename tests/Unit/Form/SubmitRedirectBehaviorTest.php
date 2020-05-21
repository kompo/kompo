<?php

namespace Kompo\Tests\Unit\Form;

use Illuminate\Auth\Access\AuthorizationException;
use Kompo\Tests\EnvironmentBoot;

class SubmitRedirectBehaviorTest extends EnvironmentBoot
{
	/** @test */
	public function form_has_submit_to_assigned_correctly()
	{
		$form = new _SubmitRedirectBehaviorForm();

		$this->assertEquals(url('submit-test'), $form->data('submitUrl'));
		$this->assertEquals('PUT', $form->data('submitMethod'));
		$this->assertEquals(url('redirect-test'), $form->data('redirectUrl'));
		$this->assertEquals('Redirecting message test...', $form->data('redirectMessage'));

		$this->assertNull($form->data('submitAction'));
	}

	/** @test */
	public function form_handles_submit_in_handle_method()
	{
		$form = new _SubmitHandleMethodForm();

		$this->assertEquals($this->kompoRoute, $form->data('submitUrl'));
		$this->assertEquals('POST', $form->data('submitMethod'));
		$this->assertArrayNotHasKey('redirectUrl', $form->data());
		$this->assertArrayNotHasKey('redirectMessage', $form->data());

		$this->assertEquals('handle-submit', $form->data('submitAction'));

		$this->submit($form)
			->assertStatus(200)
			->assertSee('test custom handle string');

	}

	/** @test */
	public function form_handles_submit_in_eloquent_save_method()
	{
		$form = new _SubmitEloquentSaveForm();

		$this->assertEquals($this->kompoRoute, $form->data('submitUrl'));
		$this->assertEquals('POST', $form->data('submitMethod'));
		$this->assertArrayNotHasKey('redirectUrl', $form->data());
		$this->assertArrayNotHasKey('redirectMessage', $form->data());

		$this->assertEquals('eloquent-submit', $form->data('submitAction'));

		$this->submit($form)
			->assertStatus(201)
			->assertJson([
				'id' => 1
			]);
	}

	/** @test */
	public function form_has_submit_prevented_by_property()
	{
		$this->expectException(AuthorizationException::class);

		$form = new _PreventSubmitForm();

		$this->assertNull($form->data('submitUrl'));
		$this->assertNull($form->data('submitMethod'));
		$this->assertNull($form->data('redirectUrl'));
		$this->assertNull($form->data('redirectMessage'));

		$this->assertNull($form->data('submitAction'));

		$form->data(['submitAction' => 'handle-submit']); //hack to force test

		$this->withoutExceptionHandling()->submit($form);
	}


}