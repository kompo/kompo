<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;
use Kompo\Tests\Models\User;

class CatalogFiltersEloquentRelationsTest extends EnvironmentBoot
{
	protected $ctxt1 = 'a$w3IRdTe#t1';

	protected $ctxt2 = 'x^^y $mtH3lS*';

	/** @test */
	public function filters_work_for_relations_in_eloquent_builder()
	{
		$this->filters_work_for_relations(new _QueryEloquentBuilder());
	}

	/** @test */
	public function filters_work_for_relations_in_eloquent_model()
	{
		//$this->filters_work_for_attributes(new _QueryEloquentModel());
	}

	/** @test */
	public function filters_work_for_relations_in_eloquent_model_property()
	{
		//$this->filters_work_for_attributes(new _QueryEloquentModelProperty());
	}

	/** @test */
	public function filters_work_for_relations_in_eloquent_relation()
	{
		factory(Post::class)->create();

		//$this->filters_work_for_attributes(new _QueryEloquentRelation());
	}

	private function filters_work_for_relations($catalog)
	{
		$objs = factory(Obj::class, 10)->create();
		$user2 = factory(User::class)->create(['name' => 'peter']); //name larger than 'm' to disqualify in Filtered query

		$response = $this->browse($catalog, [
			'belongsToPlain' => 1,
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response);

		$response = $this->browse($catalog, [
			'belongsToPlain.name' => 'pe',
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response, false);
		
		$response = $this->browse($catalog, [
			'belongsToOrdered.name' => substr($user2->name, -4),
		])->assertStatus(200);

		$this->assert_items_are_the_ones_expected($response, false);
		
		$response = $this->browse($catalog, [
			'belongsToFiltered.name' => $user2->name,
		])->assertStatus(200);

		$this->assertCount(0, $response->decodeResponseJson()['data']); //no results for Filtered

		$nonMatchingPosts = factory(Post::class, 10)->create();
		$matchingPost = factory(Post::class)->create(['title' => 'unlikely to have bl3# title in faker']);

		$response = $this->browse($catalog, [
			'belongsToPlain.posts.title' => 'have bl3# title',
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