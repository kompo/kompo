<?php

namespace Kompo\Tests\Feature\Place;

class PlacesStoredAsHasOneManyTest extends PlaceEnvironmentBoot
{
    protected $currentRelation;

    /** @test */
    public function place_upload_works_with_has_one_plain_crud()
    {
        $this->assert_has_one_place('hasOnePlain2', 'has_one_plain2');
    }

    /** @test */
    public function place_upload_works_with_has_one_ordered_crud()
    {
        $this->assert_has_one_place('hasOneOrdered2', 'has_one_ordered2');
    }

    /** @test */
    public function place_upload_works_with_has_one_filtered_crud()
    {
        $this->assert_has_one_place('hasOneFiltered2', 'has_one_filtered2');
    }

    /** @test */
    public function place_upload_works_with_has_many_plain_crud()
    {
        $this->assert_has_many_places('hasManyPlain2', 'has_many_plain2');
    }

    /** @test */
    public function place_upload_works_with_has_many_ordered_crud()
    {
        $this->assert_has_many_places('hasManyOrdered2', 'has_many_ordered2');
    }

    /** @test */
    public function place_upload_works_with_has_many_filtered_crud()
    {
        $this->assert_has_many_places('hasManyFiltered2', 'has_many_filtered2');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_has_one_place($relation, $snaked)
    {
        $this->currentRelation = $relation;

        //Insert
        $this->submit(
            $form = $this->getForm(),
            [
                $relation => [$place1 = $this->createPlace('123 St B')],
            ]
        )->assertStatus(201)
        ->assertJson([
            $snaked => $this->place_to_array($place1),
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));

        //Reload
        $form = $this->getForm(1);
        $this->assertEquals(1, $form->elements[0]->value->id);
        $this->assertSubset($this->place_to_array($place1), $form->elements[0]->value);

        //Update
        $this->submit(
            $form = $this->getForm(1),
            [
                $relation => [$place2 = $this->createPlace('456 St A')],
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => $this->place_to_array($place2),
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place2));

        //Reload
        $form = $this->getForm(1);
        $this->assertEquals(2, $form->elements[0]->value->id);
        $this->assertSubset($this->place_to_array($place2), $form->elements[0]->value);

        //Remove
        $this->submit(
            $form = $this->getForm(1),
            [
                $relation => null,
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => null,
        ]);

        $this->assertDatabaseMissing('places', $this->place_to_array($place2));
        $this->assertEquals(0, \DB::table('places')->count());

        //Reload
        $form = $this->getForm(1);
        $this->assertNull($form->elements[0]->value);
    }

    private function assert_has_many_places($relation, $snaked)
    {
        $this->currentRelation = $relation;

        //Insert
        $this->submit(
            $form = $this->getForm(),
            [
                $relation => [$place1 = $this->createPlace('83 St B'), $place2 = $this->createPlace('123 St B')],
            ]
        )->assertStatus(201)
        ->assertJson([
            $snaked => $relation == 'hasManyOrdered2' ?
                [$this->place_to_array($place2), $this->place_to_array($place1)] :
                [$this->place_to_array($place1), $this->place_to_array($place2)],
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));
        $this->assertDatabaseHas('places', $this->place_to_array($place2));

        //Reload
        $form = $this->getForm(1);
        $this->assertCount(2, $form->elements[0]->value);
        if ($relation == 'hasManyOrdered2') {
            $this->assertSubset($this->place_to_array($place2), $form->elements[0]->value[0]);
            $this->assertSubset($this->place_to_array($place1), $form->elements[0]->value[1]);
        } else {
            $this->assertSubset($this->place_to_array($place1), $form->elements[0]->value[0]);
            $this->assertSubset($this->place_to_array($place2), $form->elements[0]->value[1]);
        }
        if ($relation == 'hasManyFiltered2') {
            $this->assertEquals(1, $form->elements[0]->value[0]->order);
        }

        //Update
        $this->submit(
            $form = $this->getForm(1),
            [
                $relation => [$place1, $place3 = $this->createPlace('93 St B'), $place4 = $this->createPlace('23 St B')],
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => $relation == 'hasManyOrdered2' ?
                [$this->place_to_array($place4), $this->place_to_array($place1), $this->place_to_array($place3)] :
                [$this->place_to_array($place1), $this->place_to_array($place3), $this->place_to_array($place4)],
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));
        $this->assertDatabaseMissing('places', $this->place_to_array($place2));
        $this->assertDatabaseHas('places', $this->place_to_array($place3));
        $this->assertDatabaseHas('places', $this->place_to_array($place4));

        //Reload
        $form = $this->getForm(1);
        $this->assertCount(3, $form->elements[0]->value);
        if ($relation == 'hasManyOrdered2') {
            $this->assertSubset($this->place_to_array($place4), $form->elements[0]->value[0]);
            $this->assertSubset($this->place_to_array($place1), $form->elements[0]->value[1]);
            $this->assertSubset($this->place_to_array($place3), $form->elements[0]->value[2]);
        } else {
            $this->assertSubset($this->place_to_array($place1), $form->elements[0]->value[0]);
            $this->assertSubset($this->place_to_array($place3), $form->elements[0]->value[1]);
            $this->assertSubset($this->place_to_array($place4), $form->elements[0]->value[2]);
        }
        if ($relation == 'hasManyFiltered2') {
            $this->assertEquals(1, $form->elements[0]->value[0]->order);
        }

        //Remove
        $this->submit(
            $form = $this->getForm(1),
            [
                $relation => null,
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => null,
        ]);

        $this->assertDatabaseMissing('places', $this->place_to_array($place1));
        $this->assertDatabaseMissing('places', $this->place_to_array($place3));
        $this->assertDatabaseMissing('places', $this->place_to_array($place4));
        $this->assertEquals(0, \DB::table('places')->count());

        //Reload
        $form = $this->getForm(1);
        $this->assertNull($form->elements[0]->value);
    }

    protected function getForm($id = null)
    {
        return _PlacesStoredAsHasOneHasManyForm::boot($id, [
            'element' => $this->currentRelation,
        ]);
    }
}
