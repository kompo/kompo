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
	public function unauthorized_boot_throws_an_error_for_forms()
	{
		$this->assert_unauthorized_boot(_BootUnauthorizedForm::class);
	}

    /** @test */
	public function boot_is_authorized_for_querys()
	{
		$this->assert_authorized_boot(_Query());
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_querys()
	{
		$this->assert_unauthorized_boot(_BootUnauthorizedQuery::class);
	}

    /** @test */
	public function boot_is_authorized_for_menus()
	{
		$this->assert_authorized_boot(_Menu());
	}

    /** @test */
	public function unauthorized_boot_throws_an_error_for_menus()
	{
		$this->assert_unauthorized_boot(_BootUnauthorizedMenu::class);
	}

	/** ------------------ PRIVATE --------------------------- */    

	private function assert_authorized_boot($obj)
	{
		$this->assertTrue(true);
	}

	private function assert_unauthorized_boot($objClass)
	{
		$this->expectException(UnauthorizedException::class);
		
		$obj = new $objClass();
	}
}