<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\User;

class SelectStoredAsBelongsToManyTest extends EnvironmentBoot
{
    /** @test */
    public function select_works_with_belongs_to_plain_crud()
    {
    	$this->assert_belongs_to_select('belongsToPlain', 'belongs_to_plain', 0);
    }

    /** @test */
    public function select_works_with_belongs_to_ordered_crud()
    {
    	$this->assert_belongs_to_select('belongsToOrdered', 'belongs_to_ordered', 1);
    }
    
    /** @test */
    public function select_works_with_belongs_to_filtered_crud()
    {
    	$this->assert_belongs_to_select('belongsToFiltered', 'belongs_to_filtered', 2);
    }

    /** @test */
    public function select_works_with_belongs_to_many_plain_crud()
    {
    	$this->assert_belongs_to_many_selects('belongsToManyPlain', 'belongs_to_many_plain', 3);
    }
    
    /** @test */
    public function select_works_with_belongs_to_many_ordered_crud()
    {
    	$this->assert_belongs_to_many_selects('belongsToManyOrdered', 'belongs_to_many_ordered', 4);
    }
    
    /** @test */
    public function select_works_with_belongs_to_many_filtered_crud()
    {
    	$this->assert_belongs_to_many_selects('belongsToManyFiltered', 'belongs_to_many_filtered', 5);
    }


    /** ------------------ PRIVATE --------------------------- */ 


    private function assert_belongs_to_select($relation, $snaked, $index)
    {	
	    //Insert
        $user1 = User::first();
        $expUser1Array = array_merge($user1->toArray(), ['order' => $relation == 'belongsToFiltered' ? 1 : null]);
        $this->submit(
        	$form = new _SelectStoredAsBelongsToManyForm(), [
        		$relation => $user1->id
        	]
        )->assertStatus(201)
        ->assertJson([
        	$snaked => $expUser1Array
        ]);

        $this->assertDatabaseHas('objs', ['user_id' => $user1->id]);
        if($relation == 'belongsToManyFiltered')
            $this->assertDatabaseHas('users', $expUser1Array); //to check he has order = 1

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);
        $this->assertEquals($user1->id, $form->components[$index]->value);

		//Update
        $user2 = factory(User::class)->create();
        $expUser2Array = array_merge($user2->toArray(), ['order' => $relation == 'belongsToFiltered' ? 1 : null]);
		$this->submit(
        	$form = new _SelectStoredAsBelongsToManyForm(1), [
        		$relation => $user2->id
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => $expUser2Array
        ]);

        $this->assertDatabaseHas('objs', ['user_id' => $user2->id]);
        if($relation == 'belongsToManyFiltered')
            $this->assertDatabaseHas('users', $expUser2Array); //to check he has order = 1

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);
        $this->assertEquals($user2->id, $form->components[$index]->value);

		//Remove
		$this->submit(
        	$form = new _SelectStoredAsBelongsToManyForm(1), [
        		$relation => null
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => null
        ]);

        $this->assertDatabaseMissing('objs', ['user_id' => $user2->id]);
        $this->assertEquals(2, \DB::table('users')->count());

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);
        $this->assertNull($form->components[$index]->value);
    }

    private function assert_belongs_to_many_selects($relation, $snaked, $index)
    {	
    	//Insert
        $file1 = factory(File::class)->create(['name' => 'g']); 
        $file2 = factory(File::class)->create(['name' => 'a']); 
        
        $this->submit($form = new _SelectStoredAsBelongsToManyForm(), [
    		$relation => [$file1->id, $file2->id]
    	])
        ->assertStatus(201)
        ->assertJson([
        	$snaked => $relation == 'belongsToManyOrdered' ? [$file2->toArray(), $file1->toArray()] :	[$file1->toArray(), $file2->toArray()]
        ]);

        $this->assert_database_has_expected_row($file1, $relation);
        $this->assert_database_has_expected_row($file2, $relation);

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);
        $this->assertCount(2, $form->components[$index]->value);
        if($relation == 'belongsToManyOrdered'){
        	$this->assertSubset($file2, $form->components[$index]->value[0]);
        	$this->assertSubset($file1, $form->components[$index]->value[1]);
        }else{
        	$this->assertSubset($file1, $form->components[$index]->value[0]);
        	$this->assertSubset($file2, $form->components[$index]->value[1]);
        }
        if($relation == 'belongsToManyFiltered')
            $this->assertEquals(1, $form->components[$index]->value[0]->order);


		//Update
        $file3 = factory(File::class)->create(['name' => 'z']); 
        $file4 = factory(File::class)->create(['name' => 'f']);

		$this->submit($form = new _SelectStoredAsBelongsToManyForm(1), [
    		$relation => [$file1->id, $file3->id, $file4->id]
    	])
        ->assertStatus(200)
        ->assertJson([$snaked => 
             $relation == 'belongsToManyOrdered' ?  [$file4->toArray(), $file1->toArray(), $file3->toArray()] : 
            ($relation == 'belongsToManyFiltered' ? [$file1->toArray(), $file4->toArray()] : 
                                                    [$file1->toArray(), $file3->toArray(), $file4->toArray()])
                            
        ]);

        $this->assertEquals(3, \DB::table('file_obj')->count());
        $this->assert_database_has_expected_row($file1, $relation);
        $this->assert_database_has_expected_row($file3, $relation);
        $this->assert_database_has_expected_row($file4, $relation);

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);

        if($relation == 'belongsToManyOrdered'){
            $this->assertCount(3, $form->components[$index]->value);
            $this->assertSubset($file4, $form->components[$index]->value[0]);
            $this->assertSubset($file1, $form->components[$index]->value[1]);
            $this->assertSubset($file3, $form->components[$index]->value[2]);

	    }elseif($relation == 'belongsToManyFiltered'){
            $this->assertCount(2, $form->components[$index]->value);
            $this->assertEquals(1, $form->components[$index]->value[0]->order);
            $this->assertSubset($file1, $form->components[$index]->value[0]);
            $this->assertSubset($file4, $form->components[$index]->value[1]);

        }else{
            $this->assertCount(3, $form->components[$index]->value);
            $this->assertSubset($file1, $form->components[$index]->value[0]);
            $this->assertSubset($file3, $form->components[$index]->value[1]);
            $this->assertSubset($file4, $form->components[$index]->value[2]);
	    }

		//Remove
		$this->submit(
        	$form = new _SelectStoredAsBelongsToManyForm(1), [
        		$relation => null
        	]
        )->assertStatus(200)
        ->assertJson([
        	$snaked => null
        ]);

        $this->assertEquals(0, \DB::table('file_obj')->count());

        //Reload
        $form = new _SelectStoredAsBelongsToManyForm(1);
        $this->assertNull($form->components[$index]->value);
    }


    /************* PRIVATE ********************/
    private function assert_database_has_expected_row($file, $relation = null)
    {
        return $this->assertDatabaseHas('file_obj', [
            'obj_id' => 1,
            'file_id' => $file->id,
            'order' => $relation == 'belongsToManyFiltered' ? 1 : null
        ]);
    }
}