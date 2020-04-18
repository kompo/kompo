<?php
namespace Kompo\Tests\Feature\Routing;

use Kompo\Tests\EnvironmentBoot;

class GetsRouteParametersTest extends EnvironmentBoot
{
    /** @test */
	public function route_info_is_correctly_set_in_forms()
	{
		\Route::get('test/{param}/{opt?}', function() {	return new _RouteParametersForm(); });

		$r = $this->get('test/1'); //different routes below, different assertions

		$kompoId = $r->decodeResponseJson()['data']['kompoId'];

		$r->assertJson([
			'parameters' => ['param' => 1],
			'param1' => 1,
			'param2' => null
		])
		->assertSessionHas('bootedElements.'.$kompoId.'.parameters.param', 1)
		->assertSessionMissing('bootedElements.'.$kompoId.'.parameters.opt')
		->assertSessionHas('bootedElements.'.$kompoId.'.uri', 'test/{param}/{opt?}')
		->assertSessionHas('bootedElements.'.$kompoId.'.method', 'GET');
	}

    /** @test */
	public function route_info_is_correctly_set_in_querys()
	{
		\Route::post('test/{param}/{opt?}', function() {	return new _RouteParametersQuery(); });

		$r = $this->post('test/1/2');

		$kompoId = $r->decodeResponseJson()['data']['kompoId'];

		$r->assertJson([
			'parameters' => ['param' => 1, 'opt' => 2],
			'param1' => 1,
			'param2' => 2
		])
		->assertSessionHas('bootedElements.'.$kompoId.'.parameters.param', 1)
		->assertSessionHas('bootedElements.'.$kompoId.'.parameters.opt', 2)
		->assertSessionHas('bootedElements.'.$kompoId.'.uri', 'test/{param}/{opt?}')
		->assertSessionHas('bootedElements.'.$kompoId.'.method', 'POST');
	}

    /** @test */
	public function route_info_is_correctly_set_in_menus()
	{
		\Route::put('test/{param}/{opt?}', function() {	return new _RouteParametersMenu(); });

		$r = $this->put('test/hello%20world/');

		$kompoId = $r->decodeResponseJson()['data']['kompoId'];

		$r->assertJson([
			'parameters' => ['param' => 'hello world'],
			'param1' => 'hello world',
			'param2' => null
		])
		->assertSessionHas('bootedElements.'.$kompoId.'.parameters.param', 'hello world')
		->assertSessionMissing('bootedElements.'.$kompoId.'.parameters.opt')
		->assertSessionHas('bootedElements.'.$kompoId.'.uri', 'test/{param}/{opt?}')
		->assertSessionHas('bootedElements.'.$kompoId.'.method', 'PUT');
	}
}