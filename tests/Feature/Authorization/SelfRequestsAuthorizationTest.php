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
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedForm(), 'Get');
	}
    /** @test */
	public function self_post_request_unauthorized_for_form()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedForm(), 'Post');
	}
    /** @test */
	public function self_put_request_unauthorized_for_form()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedForm(), 'Put');
	}
    /** @test */
	public function self_delete_request_unauthorized_for_form()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedForm(), 'Delete');
	}

	/******** Query ************/

    /** @test */
	public function self_get_request_unauthorized_for_query()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedQuery(), 'Get');
	}
    /** @test */
	public function self_post_request_unauthorized_for_query()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedQuery(), 'Post');
	}
    /** @test */
	public function self_put_request_unauthorized_for_query()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedQuery(), 'Put');
	}
    /** @test */
	public function self_delete_request_unauthorized_for_query()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedQuery(), 'Delete');
	}

	/******** Menu ************/

    /** @test */
	public function self_get_request_unauthorized_for_menu()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedMenu(), 'Get');
	}
    /** @test */
	public function self_post_request_unauthorized_for_menu()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedMenu(), 'Post');
	}
    /** @test */
	public function self_put_request_unauthorized_for_menu()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedMenu(), 'Put');
	}
    /** @test */
	public function self_delete_request_unauthorized_for_menu()
	{
		$this->self_request_unauthorized_for(new _SelfRequestUnauthorizedMenu(), 'Delete');
	}

	/** ------------------ PRIVATE --------------------------- */    

	private function self_request_unauthorized_for($komposer, $type)
	{
		$this->expectException(AuthorizationException::class);

		$this->withoutExceptionHandling()->{'self'.$type}($komposer, 'whatever');

	}
}