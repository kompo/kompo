<?php

namespace Kompo\Tests\Feature\Query;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;
use Kompo\Tests\Models\User;

class QueryFiltersEloquentRelationsTest extends EnvironmentBoot
{
    protected $ctxt1 = 'a$w3IRdTe#t1';

    protected $ctxt2 = 'x^^y $mtH3lS*';

    /** @test */
    public function filters_work_for_relations_in_eloquent_builder()
    {
        $this->filters_work_for_relations(_QueryEloquentBuilder::boot());
    }

    /** @test */
    public function filters_work_for_relations_in_eloquent_model()
    {
        $this->filters_work_for_relations(_QueryEloquentModel::boot());
    }

    /** @test */
    public function filters_work_for_relations_in_eloquent_model_property()
    {
        $this->filters_work_for_relations(_QueryEloquentModelProperty::boot());
    }

    /** @test */
    public function filters_work_for_relations_in_eloquent_relation()
    {
        factory(Post::class)->create();

        $this->filters_work_for_relations(_QueryEloquentRelation::boot());
    }

    /********** PRIVATE ******************************************/

    private function filters_work_for_relations($query)
    {
        $objs = factory(Obj::class, 11)->create();  //Factory alternates values depending on an iterator
        $user2 = factory(User::class)->create(['name' => 'peter']); //name larger than 'm' to disqualify in Filtered query

        // BelongsTo
        $this->assert_the_expected_items_are_returned($query, [
            'belongsToPlain' => 1,
        ]);

        // MorphTo
        $this->assert_the_expected_items_are_returned($query, [
            'morphToPlain' => 1,
        ]);

        // Relation + Attribute
        $this->assert_the_expected_items_are_returned($query, [
            'belongsToPlain.name' => 'pe',
        ], false);

        // Ordered Relation + Attribute
        $this->assert_the_expected_items_are_returned($query, [
            'belongsToOrdered.name' => substr($user2->name, -4),
        ], false);

        // Filtered Relation + Attribute
        $this->assert_no_items_are_returned($query, [
            'belongsToFiltered.name' => $user2->name,
        ]); //no results for Filtered

        // Double nested Relation + Attribute
        $nonMatchingPosts = factory(Post::class, 10)->create();
        $matchingPost = factory(Post::class)->create(['title' => 'unlikely to have bl3# title in faker']);

        $this->assert_the_expected_items_are_returned($query, [
            'belongsToPlain.posts.title' => 'have bl3# title',
        ]);

        //Preparing new objects for the rest of the relationships
        $files = factory(File::class, 5)->create();  //Factory alternates values depending on an iterator (2 odd / 3 even here)
        $fmtm = factory(File::class, 4)->create([
            'obj_id'     => null,
            'model_id'   => null,
            'model_type' => null,
        ]);
        $nonExistingFileId = 2999;

        //BelongsToMany
        foreach ($objs as $key => $obj) {
            if ($key % 2 == 1) {
                $obj->belongsToManyPlain()->sync([$fmtm[0]->id, $fmtm[1]->id]);
                $obj->morphToManyPlain()->sync([$fmtm[0]->id, $fmtm[1]->id]);
                $obj->morphedByManyPlain()->sync([$fmtm[0]->id, $fmtm[1]->id]);
            } else {
                $obj->belongsToManyPlain()->sync([$fmtm[2]->id]);
                $obj->morphToManyPlain()->sync([$fmtm[2]->id]);
                $obj->morphedByManyPlain()->sync([$fmtm[2]->id]);
            }
        }
        $this->assert_the_expected_items_are_returned($query, [
            'belongsToManyOrdered' => [$fmtm[0]->id],
        ], false);
        $this->assert_no_items_are_returned($query, [
            'belongsToManyOrdered' => [$fmtm[3]->id],
        ]);

        //MorphToMany
        $this->assert_the_expected_items_are_returned($query, [
            'morphToManyOrdered' => [$fmtm[0]->id, $fmtm[1]->id],
        ], false);
        $this->assert_no_items_are_returned($query, [
            'morphToManyOrdered' => [$fmtm[3]->id],
        ]);

        //MorphedByMany
        $this->assert_the_expected_items_are_returned($query, [
            'morphedByManyOrdered' => [$fmtm[1]->id, $fmtm[3]->id],
        ], false);
        $this->assert_no_items_are_returned($query, [
            'morphedByManyOrdered' => [$fmtm[3]->id],
        ]);

        //HasOne
        $this->assert_the_expected_item_is_returned($query, [
            'hasOneOrdered' => $files[0]->id,
        ]);
        $this->assert_no_items_are_returned($query, [
            'hasOneOrdered' => $nonExistingFileId,
        ]);

        //HasMany
        $this->assert_the_expected_item_is_returned($query, [
            'hasManyOrdered' => [$files[0]->id],
        ]);
        $this->assert_no_items_are_returned($query, [
            'hasManyOrdered' => [$nonExistingFileId],
        ]);

        //MorphOne
        $this->assert_the_expected_item_is_returned($query, [
            'morphOneOrdered' => $files[0]->id,
        ]);
        $this->assert_no_items_are_returned($query, [
            'morphOneOrdered' => $nonExistingFileId,
        ]);

        //MorphOne
        $this->assert_the_expected_item_is_returned($query, [
            'morphManyOrdered' => [$files[0]->id],
        ]);
        $this->assert_no_items_are_returned($query, [
            'morphManyOrdered' => [$nonExistingFileId],
        ]);
    }

    /******** PRIVATE ^2 ************************************/

    private function assert_the_expected_items_are_returned($query, $data, $odd = true)
    {
        $items = $this->browse($query, $data)
            ->assertStatus(200)
            ->decodeResponseJson()['data'];

        $this->assertCount($odd ? 6 : 5, $items);
        foreach ($items as $item) {
            $this->assertEquals($odd ? 1 : 0, $item['render']['komponents']['id'] % 2);
            $this->assertEquals($odd ? 1 : 2, $item['render']['komponents']['user_id']);
        }
    }

    private function assert_the_expected_item_is_returned($query, $data)
    {
        $items = $this->browse($query, $data)
            ->assertStatus(200)
            ->decodeResponseJson()['data'];

        $this->assertCount(1, $items);
        $this->assertEquals(1, $items[0]['render']['komponents']['id']);
    }

    private function assert_no_items_are_returned($query, $data)
    {
        $items = $this->browse($query, $data)
            ->assertStatus(200)
            ->decodeResponseJson()['data'];

        $this->assertCount(0, $items);
    }
}
