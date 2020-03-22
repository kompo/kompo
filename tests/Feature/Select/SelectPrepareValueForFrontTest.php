<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\IconText;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class SelectPrepareValueForFrontTest extends EnvironmentBoot
{
	/** @test */
	public function expected_value_is_assigned_for_options()
	{	
		factory(Tag::class, 6)->create();		
		Obj::unguard();
		$obj = Obj::create([
			'tag' => 1,
			'tags' => json_encode([3,4]),
			'tags_cast' => [2,5]
		]);

		$form = new _SelectAttributeFillsForm(1);
		$this->assert_attributes_are_correctly_transformed($form);

		$form = new _SelectAttributeFillsForm(1, ['optionsMethod' => 'Tags']);
		$this->assert_attributes_are_correctly_transformed($form);

		$form = new _SelectAttributeFillsForm(1, ['optionsMethod' => 'Cards']);
		$this->assert_attributes_are_correctly_transformed($form);
	}

	/** @test */
	public function expected_value_is_plucked_from_relationships()
	{
		//creating unordered files and names before and after m for filtering
		$file1 = factory(File::class)->create(['name' => 'q.jpg']);
		$file2 = factory(File::class)->create(['name' => 'a.jpg']);
		$file3 = factory(File::class)->create(['name' => 'l.jpg']);
		
		Obj::unguard();
		$obj = Obj::create(['user_id' => 1]); //belongsTo
		$obj->belongsToManyPlain()->attach([$file1->id, $file2->id, $file3->id]); //belongsToMany

		$form = new _SelectOptionsFromForm(1);

		//belongsTo
		$this->assertEquals(1, $form->components[0]->value);
		$this->assertEquals(1, $form->components[1]->value);
		$this->assertEquals(1, $form->components[2]->value);

		//belongsToPlain
		$this->assertCount(3, $form->components[3]->value);
		$this->assertEquals($file1->id, $form->components[3]->value[0]);
		$this->assertEquals($file2->id, $form->components[3]->value[1]);
		$this->assertEquals($file3->id, $form->components[3]->value[2]);

		//belongsToOrdered
		$this->assertCount(3, $form->components[4]->value);
		$this->assertEquals($file2->id, $form->components[4]->value[0]);
		$this->assertEquals($file3->id, $form->components[4]->value[1]);
		$this->assertEquals($file1->id, $form->components[4]->value[2]);

		//belongsToFiltered
		$this->assertCount(2, $form->components[5]->value);
		$this->assertEquals($file2->id, $form->components[5]->value[0]);
		$this->assertEquals($file3->id, $form->components[5]->value[1]);

	}


	/*************** PRIVATE ***********************************/


	private function assert_attributes_are_correctly_transformed($form)
	{
		$this->assertEquals(1, $form->components[0]->value);
		$this->assertCount(2, $form->components[1]->value);
		$this->assertEquals(3, $form->components[1]->value[0]);
		$this->assertEquals(4, $form->components[1]->value[1]);
		$this->assertEquals(2, $form->components[2]->value[0]);
		$this->assertEquals(5, $form->components[2]->value[1]);
	}
}