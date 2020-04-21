<?php

namespace Kompo\Tests\Feature\Place;

class PlacesStoredAsMorphOneManyTest extends PlaceEnvironmentBoot
{
    /** @test */
    public function place_upload_works_with_morph_one_plain_crud()
    {
    	$this->assert_morph_one_place('morphOnePlain2', 'morph_one_plain2', 0);
    }

    /** @test */
    public function place_upload_works_with_morph_one_ordered_crud()
    {
    	$this->assert_morph_one_place('morphOneOrdered2', 'morph_one_ordered2', 1);
    }
    
    /** @test */
    public function place_upload_works_with_morph_one_filtered_crud()
    {
    	$this->assert_morph_one_place('morphOneFiltered2', 'morph_one_filtered2', 2);
    }

    /** @test */
    public function place_upload_works_with_morph_many_plain_crud()
    {
    	$this->assert_morph_many_places('morphManyPlain2', 'morph_many_plain2', 3);
    }
    
    /** @test */
    public function place_upload_works_with_morph_many_ordered_crud()
    {
    	$this->assert_morph_many_places('morphManyOrdered2', 'morph_many_ordered2', 4);
    }
    
    /** @test */
    public function place_upload_works_with_morph_many_filtered_crud()
    {
    	$this->assert_morph_many_places('morphManyFiltered2', 'morph_many_filtered2', 5);
    }


    /** ------------------ PRIVATE --------------------------- */ 


    private function assert_morph_one_place($relation, $snaked, $index)
    {	
	    //Insert
        $this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(), [
        		$relation => [ $place1 = $this->createPlace('12  St df') ]
        	]
        )->assertStatus(201)
        ->assertJson([
        	$snaked => $this->place_to_array($place1)
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertEquals(1, $form->komponents[$index]->value->id);
        $this->assertSubset($this->place_to_array($place1), $form->komponents[$index]->value);

		//Update
		$this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(1), [
        		$relation => [ $place2 = $this->createPlace('652 St df') ]
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => $this->place_to_array($place2)
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place2));

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertEquals(2, $form->komponents[$index]->value->id);
        $this->assertSubset($this->place_to_array($place2), $form->komponents[$index]->value);

		//Remove
		$this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(1), [
        		$relation => null
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => null
        ]);

        $this->assertDatabaseMissing('places', $this->place_to_array($place2));
        $this->assertEquals(0, \DB::table('places')->count());

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertNull($form->komponents[$index]->value);
    }

    private function assert_morph_many_places($relation, $snaked, $index)
    {	
    	//Insert
        $this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(), [
        		$relation => [$place1 = $this->createPlace('83 St B'), $place2 = $this->createPlace('123 St B')]
        	]
        )->assertStatus(201)
        ->assertJson([
        	$snaked => $relation == 'morphManyOrdered2' ? 
        		[$this->place_to_array($place2), $this->place_to_array($place1)] :
        		[$this->place_to_array($place1), $this->place_to_array($place2)]
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));
        $this->assertDatabaseHas('places', $this->place_to_array($place2));

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertCount(2, $form->komponents[$index]->value);
        if($relation == 'morphManyOrdered2'){
        	$this->assertSubset($this->place_to_array($place2), $form->komponents[$index]->value[0]);
        	$this->assertSubset($this->place_to_array($place1), $form->komponents[$index]->value[1]);
        }else{
        	$this->assertSubset($this->place_to_array($place1), $form->komponents[$index]->value[0]);
        	$this->assertSubset($this->place_to_array($place2), $form->komponents[$index]->value[1]);
        }
        if($relation == 'morphManyFiltered2')
            $this->assertEquals(1, $form->komponents[$index]->value[0]->order);


		//Update
		$this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(1), [
        		$relation => [$place1, $place3 = $this->createPlace('93 St B'), $place4 = $this->createPlace('23 St B')]
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => $relation == 'morphManyOrdered2' ? 
        		[$this->place_to_array($place4), $this->place_to_array($place1), $this->place_to_array($place3)] :
        		[$this->place_to_array($place1), $this->place_to_array($place3), $this->place_to_array($place4)]
        ]);

        $this->assertDatabaseHas('places', $this->place_to_array($place1));
        $this->assertDatabaseMissing('places', $this->place_to_array($place2));
        $this->assertDatabaseHas('places', $this->place_to_array($place3));
        $this->assertDatabaseHas('places', $this->place_to_array($place4));

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertCount(3, $form->komponents[$index]->value);
        if($relation == 'morphManyOrdered2'){
	        $this->assertSubset($this->place_to_array($place4), $form->komponents[$index]->value[0]);
	        $this->assertSubset($this->place_to_array($place1), $form->komponents[$index]->value[1]);
	        $this->assertSubset($this->place_to_array($place3), $form->komponents[$index]->value[2]);
	    }else{
	        $this->assertSubset($this->place_to_array($place1), $form->komponents[$index]->value[0]);
	        $this->assertSubset($this->place_to_array($place3), $form->komponents[$index]->value[1]);
	        $this->assertSubset($this->place_to_array($place4), $form->komponents[$index]->value[2]);
	    }
        if($relation == 'morphManyFiltered2')
            $this->assertEquals(1, $form->komponents[$index]->value[0]->order);

		//Remove
		$this->submit(
        	$form = new _PlacesStoredAsMorphOneMorphManyForm(1), [
        		$relation => null
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => null
        ]);

        $this->assertDatabaseMissing('places', $this->place_to_array($place1));
        $this->assertDatabaseMissing('places', $this->place_to_array($place3));
        $this->assertDatabaseMissing('places', $this->place_to_array($place4));
        $this->assertEquals(0, \DB::table('places')->count());

        //Reload
        $form = new _PlacesStoredAsMorphOneMorphManyForm(1);
        $this->assertNull($form->komponents[$index]->value);
    }
}