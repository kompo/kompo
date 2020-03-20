<?php
namespace Kompo\Tests\Unit\Komposer;

use Kompo\Tests\EnvironmentBoot;

class HasStoreTest extends EnvironmentBoot
{
    /** @test */
	public function store_works_for_forms()
	{
		$this->make_store_assertions(_Form(null, $this->initialStore));
		$this->make_empty_store_assertions(_Form());		
	}
    /** @test */
	public function store_works_for_catalogs()
	{
		$this->make_store_assertions(_Catalog($this->initialStore));
		$this->make_empty_store_assertions(_Catalog());		
	}
    /** @test */
	public function store_works_for_menus()
	{
		$this->make_store_assertions(_Menu($this->initialStore));
		$this->make_empty_store_assertions(_Menu());		
	}
	
	/** ------------------ PRIVATE --------------------------- */    
	
	private $initialStore = [
		'key' => 'some-value',
		'key1' => 'bbb',
		'key2' => 0,
		'key3' => null,
		0 => 2,
		1 => 'blabl'
	];

	private $addedStore = [
		'key' => 'another-value',
		'key1' => 'bbb',
		'key2' => 2,
		0 => 3
	];

	private function make_store_assertions($obj)
	{
		$this->assertEquals($this->initialStore['key'], $obj->store('key'));
		$this->assertEquals($this->initialStore['key1'], $obj->store('key1'));
		$this->assertEquals($this->initialStore['key2'], $obj->store('key2'));
		$this->assertEquals($this->initialStore[0], $obj->store(0));
		$this->assertEquals($this->initialStore[1], $obj->store(1));
		$this->assertNull($obj->store('key3'));

		$obj->store($this->addedStore);

		$this->assertEquals($this->addedStore['key'], $obj->store('key'));
		$this->assertEquals($this->addedStore['key1'], $obj->store('key1'));
		$this->assertEquals($this->addedStore['key2'], $obj->store('key2'));
		$this->assertEquals($this->addedStore[0], $obj->store(0));
		$this->assertEquals($this->initialStore[1], $obj->store(1));
	}

	private function make_empty_store_assertions($obj)
	{
		$this->assertIsArray($obj->store());
		$this->assertCount(0, $obj->store());		
	}
}