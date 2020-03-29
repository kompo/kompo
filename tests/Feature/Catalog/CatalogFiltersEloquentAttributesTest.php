<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;

class CatalogFiltersEloquentAttributesTest extends EnvironmentBoot
{
	protected $ctxt1 = 'a$w3IRdTe#t1';

	protected $ctxt2 = 'x^^y $mtH3lS*';

	/** @test */
	public function filters_work_for_attributes_in_eloquent_builder()
	{
		$this->filters_work_for_attributes(new _QueryEloquentBuilder());
	}

	/** @test */
	public function filters_work_for_attributes_in_eloquent_model()
	{
		$this->filters_work_for_attributes(new _QueryEloquentModel());
	}

	/** @test */
	public function filters_work_for_attributes_in_eloquent_model_property()
	{
		$this->filters_work_for_attributes(new _QueryEloquentModelProperty());
	}

	/** @test */
	public function filters_work_for_attributes_in_eloquent_relation()
	{
		factory(Post::class)->create();

		$this->filters_work_for_attributes(new _QueryEloquentRelation());
	}

	private function filters_work_for_attributes($catalog)
	{
		$objs = factory(Obj::class, 10)->create();

		$response = $this->browse($catalog, [
			'title' => substr($this->ctxt1, 3, 8),
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);		

		$response = $this->browse($catalog, [
			'equal' => $this->ctxt1,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'greater' => 'm',
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response, false);

		$response = $this->browse($catalog, [
			'lower' => 500,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'like' => $this->ctxt1,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'startswith' => $this->ctxt1,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'endswith' => $this->ctxt1,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'between' => [(date('Y')-1), date('Y-m-d')],
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'in' => [1,3,5,7,9]
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);


	}


	private function assert_items_are_the_ones_expected($response, $odd = true)
	{
		$items = $response->decodeResponseJson()['data'];

		$this->assertCount(5, $items);
		foreach($items as $item){
			$this->assertEquals($odd ? 1 : 2, $item['components']['user_id']);
		}
	}
}