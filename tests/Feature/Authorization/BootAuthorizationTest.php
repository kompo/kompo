<?php
namespace Kompo\Tests\Feature\Authorization;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Exceptions\UnauthorizedException;

class BootAuthorizationTest extends EnvironmentBoot
{
    /** @test */
	public function boot_is_authorized_for_forms()
	{
		$this->assert_authorized_boot(_Form());
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_forms_display()
	{
		$this->assert_unauthorized_boot_display(_BootUnauthorizedForm::class);
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_forms_action()
	{
		$this->assert_unauthorized_boot_action(_BootUnauthorizedForm::class);
	}

    /** @test */
	public function boot_is_authorized_for_querys()
	{
		$this->assert_authorized_boot(_Query());
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_querys_display()
	{
		$this->assert_unauthorized_boot_display(_BootUnauthorizedQuery::class);
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_querys_action()
	{
		$this->assert_unauthorized_boot_action(_BootUnauthorizedQuery::class);
	}

    /** @test */
	public function boot_is_authorized_for_menus()
	{
		$this->assert_authorized_boot(_Menu());
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_menus_display()
	{
		$this->assert_unauthorized_boot_display(_BootUnauthorizedMenu::class);
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_menus_action()
	{
		$this->assert_unauthorized_boot_action(_BootUnauthorizedMenu::class);
	}

	/** ------------------ PRIVATE --------------------------- */    

	private function assert_authorized_boot($obj)
	{
		$this->assertTrue(true);
	}

	private function assert_unauthorized_boot_display($objClass)
	{
		$this->expectException(UnauthorizedException::class);
		
		$obj = new $objClass();
	}

	private function assert_unauthorized_boot_action($objClass)
	{
		\Route::kompo('test-route', $objClass);

		$this->expectException(UnauthorizedException::class);
		
		$this->withoutExceptionHandling()->get('test-route');
	}
}