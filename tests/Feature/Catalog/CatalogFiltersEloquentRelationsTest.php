<?php

namespace Kompo\Tests\Feature\Catalog;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
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
		$this->filters_work_for_relations(new _QueryEloquentModel());
	}

	/** @test */
	public function filters_work_for_relations_in_eloquent_model_property()
	{
		$this->filters_work_for_relations(new _QueryEloquentModelProperty());
	}

	/** @test */
	public function filters_work_for_relations_in_eloquent_relation()
	{
		factory(Post::class)->create();

		$this->filters_work_for_relations(new _QueryEloquentRelation());
	}

	private function filters_work_for_relations($catalog)
	{
		$objs = factory(Obj::class, 10)->create();  //Factory alternates values depending on an iterator
		$user2 = factory(User::class)->create(['name' => 'peter']); //name larger than 'm' to disqualify in Filtered query

		// BelongsTo
		$this->assert_the_five_items_are_returned($catalog, [
			'belongsToPlain' => 1,
		]);

		// Relation + Attribute
		$this->assert_the_five_items_are_returned($catalog, [
			'belongsToPlain.name' => 'pe',
		], false);

		// Ordered Relation + Attribute
		$this->assert_the_five_items_are_returned($catalog, [
			'belongsToOrdered.name' => substr($user2->name, -4),
		], false);

		// Filtered Relation + Attribute
		$response = $this->browse($catalog, [
			'belongsToFiltered.name' => $user2->name,
		])->assertStatus(200);
		$this->assertCount(0, $response->decodeResponseJson()['data']); //no results for Filtered

		// Double nested Relation + Attribute
		$nonMatchingPosts = factory(Post::class, 10)->create();
		$matchingPost = factory(Post::class)->create(['title' => 'unlikely to have bl3# title in faker']);

		$this->assert_the_five_items_are_returned($catalog, [
			'belongsToPlain.posts.title' => 'have bl3# title',
		]);

		//Preparing new objects for the rest of the relationships
		$files = factory(File::class, 5)->create();  //Factory alternates values depending on an iterator (2 odd / 3 even here)
		$fmtm = factory(File::class, 5)->create([
	        'obj_id' => null,
	        'model_id' => null,
	        'model_type' => null
		]);

		//BelongsToMany
		foreach ($objs as $key => $obj) {
			if($key % 2 == 1){
				$obj->belongsToManyPlain()->sync([$fmtm[0]->id, $fmtm[1]->id]);
			}else{
				$obj->belongsToManyPlain()->sync([$fmtm[2]->id, $fmtm[3]->id, $fmtm[4]->id]);
			}
		}
		$this->assert_the_five_items_are_returned($catalog, [
			'belongsToManyFiltered' => [$fmtm[0]->id, $fmtm[1]->id],
		], false);

		//HasOne
		$this->assert_the_expected_item_is_returned($catalog, [
			'hasOneOrdered' => $files[0]->id,
		]);

		//MorphOne
		$this->assert_the_expected_item_is_returned($catalog, [
			'morphOneFiltered' => $files[0]->id,
		]);
	}


	private function assert_the_five_items_are_returned($catalog, $data, $odd = true)
	{
		$items = $this->browse($catalog, $data)
			->assertStatus(200)
			->decodeResponseJson()['data'];

		$this->assertCount(5, $items);
		foreach($items as $item){
			$this->assertEquals($odd ? 1 : 0, $item['components']['id'] % 2);
			$this->assertEquals($odd ? 1 : 2, $item['components']['user_id']);
		}
	}

	private function assert_the_expected_item_is_returned($catalog, $data)
	{
		$items = $this->browse($catalog, $data)
			->assertStatus(200)
			->decodeResponseJson()['data'];

		$this->assertCount(1, $items);
		$this->assertEquals(1, $items[0]['components']['id']);
	}
}