<?php
namespace Kompo\Tests\Feature\Routing;

use Kompo\Exceptions\NotBootableFromRouteException;
use Kompo\Tests\EnvironmentBoot;

class KomposerRouteAsViewWithLayoutTest extends EnvironmentBoot
{
    /** @test */
	public function boot_error_for_unbootable_komponent_from_route()
	{
		$this->prepareRoute('someFakeClassString');

		$this->expectException(NotBootableFromRouteException::class);

		$this->withoutExceptionHandling()->get('test/1');
	}

    /** @test */
	public function boot_form_from_route()
	{
		$this->prepareRoute(_RouteParametersForm::class);

		$response = $this->make_route_assertions();

		$this->assertEquals(1, $response['object']->modelKey);
	}

    /** @test */
	public function boot_catalog_from_route()
	{
		$this->prepareRoute(_RouteParametersCatalog::class);

		$response = $this->make_route_assertions();
	}

    /** @test */
	public function boot_menu_from_route()
	{
		$this->prepareRoute(_RouteParametersMenu::class);

		$response = $this->make_route_assertions();
	}

	/** ------------------ PRIVATE --------------------------- */ 

	private function prepareRoute($objClass)
	{
		\Route::layout('kompo::app')->group(function() use($objClass) {
			\Route::kompo('test/{id}', $objClass);
		});
	} 

	private function make_route_assertions()
	{
		$response = $this->get('test/1')
			->assertViewIs('kompo::view')
			->assertViewHas('object')
			->assertViewHas('layout', 'kompo::app')
			->assertViewHas('section', 'content');

		$this->assertEquals(1, $response['object']->parameters['id']);
		$this->assertEquals('obj-id', $response['object']->id);

		return $response;
	}
}