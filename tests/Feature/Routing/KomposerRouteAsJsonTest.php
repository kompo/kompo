<?php
namespace Kompo\Tests\Feature\Routing;

use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Tests\EnvironmentBoot;

class KomposerRouteAsJsonTest extends EnvironmentBoot
{
    /** @test */
	public function boot_error_for_unbootable_komponent_from_route_as_json()
	{
		$this->prepareRoute('someFakeClassString');

		$this->expectException(NotBootableFromRouteException::class);

		$this->withoutExceptionHandling()->get('test/1');
	}

    /** @test */
	public function boot_form_from_route_as_json()
	{
		$this->prepareRoute(_RouteParametersForm::class);

		$this->make_route_assertions()
			->assertJson(['modelKey' => 1]);
	}

    /** @test */
	public function boot_catalog_from_route_as_json()
	{
		$this->prepareRoute(_RouteParametersCatalog::class);

		$this->make_route_assertions();
	}

    /** @test */
	public function boot_menu_from_route_as_json()
	{
		$this->prepareRoute(_RouteParametersMenu::class);

		$this->make_route_assertions();
	}

	/** ------------------ PRIVATE --------------------------- */  

	private function prepareRoute($objClass)
	{
		\Route::kompo('test/{id}', $objClass);
	} 

	private function make_route_assertions()
	{
		return $this->get('test/1')->assertJson([
			'id' => 'obj-id',
			'parameters' => ['id' => 1]
		]);
	}
}