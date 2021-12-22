<?php

namespace Kompo\Tests\Feature\Authorization;

use Illuminate\Auth\Access\AuthorizationException;
use Kompo\Tests\EnvironmentBoot;

class SelfRequestsAuthorizationTest extends EnvironmentBoot
{
    /******** Form ************/

    /** @test */
    public function self_get_request_unauthorized_for_form()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedForm::boot(), 'Get');
    }

    /** @test */
    public function self_post_request_unauthorized_for_form()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedForm::boot(), 'Post');
    }

    /** @test */
    public function self_put_request_unauthorized_for_form()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedForm::boot(), 'Put');
    }

    /** @test */
    public function self_delete_request_unauthorized_for_form()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedForm::boot(), 'Delete');
    }

    /******** Query ************/

    /** @test */
    public function self_get_request_unauthorized_for_query()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedQuery::boot(), 'Get');
    }

    /** @test */
    public function self_post_request_unauthorized_for_query()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedQuery::boot(), 'Post');
    }

    /** @test */
    public function self_put_request_unauthorized_for_query()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedQuery::boot(), 'Put');
    }

    /** @test */
    public function self_delete_request_unauthorized_for_query()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedQuery::boot(), 'Delete');
    }

    /******** Menu ************/

    /** @test */
    public function self_get_request_unauthorized_for_menu()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedMenu::boot(), 'Get');
    }

    /** @test */
    public function self_post_request_unauthorized_for_menu()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedMenu::boot(), 'Post');
    }

    /** @test */
    public function self_put_request_unauthorized_for_menu()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedMenu::boot(), 'Put');
    }

    /** @test */
    public function self_delete_request_unauthorized_for_menu()
    {
        $this->self_request_unauthorized_for(_SelfRequestUnauthorizedMenu::boot(), 'Delete');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function self_request_unauthorized_for($komponent, $type)
    {
        $this->expectException(AuthorizationException::class);

        $this->withoutExceptionHandling()->{'self'.$type}($komponent, 'whatever');
    }
}
