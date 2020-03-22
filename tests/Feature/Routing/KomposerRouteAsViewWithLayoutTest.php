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

		$this->make_route_assertions('vl-form')
			->assertSee('"modelKey":"1"');
	}

    /** @test */
	public function boot_catalog_from_route()
	{
		$this->prepareRoute(_RouteParametersCatalog::class);

		$this->make_route_assertions('vl-catalog');
	}

    /** @test */
	public function boot_menu_from_route()
	{
		$this->prepareRoute(_RouteParametersMenu::class);

		$this->make_route_assertions('vl-menu');
	}

	/** ------------------ PRIVATE --------------------------- */ 

	private function prepareRoute($komposerClass)
	{
		\Route::layout('kompo::app')->group(function() use($komposerClass) {
			\Route::kompo('test/{id}', $komposerClass);
		});
	} 

	private function make_route_assertions($vueComponent)
	{
		return $this->get('test/1')
			->assertViewIs('kompo::view')
			->assertViewHas('vueComponent')
			->assertViewHas('metaTags', [
				'title' => 'meta-title',
				'description' => 'meta description',
				'keywords' => 'key,word'
			])
			->assertViewHas('layout', 'kompo::app')
			->assertViewHas('section', 'content')
			->assertSee($vueComponent.' :vcomponent=')
			->assertSee('"id":"obj-id"')
			->assertSee('"parameters":{"id":"1"}')
			->assertSee('meta-title')
			->assertSee('meta description')
			->assertSee('key,word');
	}
}