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

        $this->assertEquals(url('submit-test'), $form->config('submitUrl'));
        $this->assertEquals('PUT', $form->config('submitMethod'));
        $this->assertEquals(url('redirect-test'), $form->config('redirectUrl'));
        $this->assertEquals('Redirecting message test...', $form->config('redirectMessage'));

        $this->assertNull($form->config('submitAction'));
    }

    /** @test */
    public function form_handles_submit_in_handle_method()
    {
        $form = new _SubmitHandleMethodForm();

        $this->assertEquals($this->kompoRoute, $form->config('submitUrl'));
        $this->assertEquals('POST', $form->config('submitMethod'));
        $this->assertArrayNotHasKey('redirectUrl', $form->config());
        $this->assertArrayNotHasKey('redirectMessage', $form->config());

        $this->assertEquals('handle-submit', $form->config('submitAction'));

        $this->submit($form)
            ->assertStatus(200)
            ->assertSee('test custom handle string');
    }

    /** @test */
    public function form_handles_submit_in_eloquent_save_method()
    {
        $form = new _SubmitEloquentSaveForm();

        $this->assertEquals($this->kompoRoute, $form->config('submitUrl'));
        $this->assertEquals('POST', $form->config('submitMethod'));
        $this->assertArrayNotHasKey('redirectUrl', $form->config());
        $this->assertArrayNotHasKey('redirectMessage', $form->config());

        $this->assertEquals('eloquent-submit', $form->config('submitAction'));

        $this->submit($form)
            ->assertStatus(201)
            ->assertJson([
                'id' => 1,
            ]);
    }

    /** @test */
    public function form_has_submit_prevented_by_property()
    {
        $this->expectException(AuthorizationException::class);

        $form = new _PreventSubmitForm();

        $this->assertNull($form->config('submitUrl'));
        $this->assertNull($form->config('submitMethod'));
        $this->assertNull($form->config('redirectUrl'));
        $this->assertNull($form->config('redirectMessage'));

        $this->assertNull($form->config('submitAction'));

        $form->config(['submitAction' => 'handle-submit']); //hack to force test

        $this->withoutExceptionHandling()->submit($form);
    }
}
