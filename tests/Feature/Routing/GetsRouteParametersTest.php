<?php
namespace Kompo\Tests\Feature\Routing;

use Illuminate\Support\Facades\Crypt;
use Kompo\Core\KompoInfo;
use Kompo\Tests\EnvironmentBoot;

class GetsRouteParametersTest extends EnvironmentBoot
{
    /** @test */
	public function route_info_is_correctly_set_in_forms()
	{
		\Route::get('test/{param}/{opt?}', function() {	return new _RouteParametersForm(); });

		$r = $this->get('test/1'); //different routes below, different assertions

		$bootInfo = Crypt::decrypt($r->decodeResponseJson()['data'][KompoInfo::$key]);

		$r->assertJson([
			'parameters' => ['param' => 1],
			'param1' => 1,
			'param2' => null
		]);

		$this->assertEquals(1, $bootInfo['parameters']['param']);
		$this->assertArrayNotHasKey('opt', $bootInfo['parameters']);
		//$this->assertEquals('test/{param}/{opt?}', $bootInfo['uri']);
		//$this->assertEquals('GET', $bootInfo['method']);
	}

    /** @test */
	public function route_info_is_correctly_set_in_querys()
	{
		\Route::post('test/{param}/{opt?}', function() {	return new _RouteParametersQuery(); });

		$r = $this->post('test/1/2');

		$bootInfo = Crypt::decrypt($r->decodeResponseJson()['data'][KompoInfo::$key]);

		$r->assertJson([
			'parameters' => ['param' => 1, 'opt' => 2],
			'param1' => 1,
			'param2' => 2
		]);

		$this->assertEquals(1, $bootInfo['parameters']['param']);
		$this->assertEquals(2, $bootInfo['parameters']['opt']);
		//$this->assertEquals('test/{param}/{opt?}', $bootInfo['uri']);
		//$this->assertEquals('POST', $bootInfo['method']);
	}

    /** @test */
	public function route_info_is_correctly_set_in_menus()
	{
		\Route::put('test/{param}/{opt?}', function() {	return new _RouteParametersMenu(); });

		$r = $this->put('test/hello%20world/');

		$bootInfo = Crypt::decrypt($r->decodeResponseJson()['data'][KompoInfo::$key]);

		$r->assertJson([
			'parameters' => ['param' => 'hello world'],
			'param1' => 'hello world',
			'param2' => null
		]);

		$this->assertEquals('hello world', $bootInfo['parameters']['param']);
		$this->assertArrayNotHasKey('opt', $bootInfo['parameters']);
		//$this->assertEquals('test/{param}/{opt?}', $bootInfo['uri']);
		//$this->assertEquals('PUT', $bootInfo['method']);
	}
}