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
	public function expected_value_is_assigned_for_attribute_for_different_options_style()
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
	public function expected_value_is_assigned_for_belongs_to()
	{		
		$this->assert_single_parent_id_is_loaded_in_component_value(['user_id' => 1], [0,1,2]);
	}

	/** @test */
	public function expected_value_is_assigned_for_belongs_to_many()
	{
		$this->assert_many_relations_are_loaded_in_component_value('belongsToMany', [3,4,5]);
	}

	/** @test */
	public function expected_value_is_assigned_for_morph_to()
	{		
		$this->assert_single_parent_id_is_loaded_in_component_value([
			'model_id' => 1,
			'model_type' => User::class
		], [6,7,8]);
	}

	/** @test */
	public function expected_value_is_assigned_for_morph_to_many()
	{
		$this->assert_many_relations_are_loaded_in_component_value('morphToMany', [9,10,11]);
	}

	/** @test */
	public function expected_value_is_assigned_for_morphed_by_many()
	{
		$this->assert_many_relations_are_loaded_in_component_value('morphedByMany', [12,13,14]);
	}


	/*************** PRIVATE ***********************************/


	private function assert_attributes_are_correctly_transformed($form)
	{
		$this->assertEquals(1, $form->components[0]->value);
		$this->assertCount(2, $form->components[1]->value);
		$this->assertEquals(3, $form->components[1]->value[0]);
		$this->assertEquals(4, $form->components[1]->value[1]);
		$this->assertCount(2, $form->components[2]->value);
		$this->assertEquals(2, $form->components[2]->value[0]);
		$this->assertEquals(5, $form->components[2]->value[1]);
	}

	private function assert_single_parent_id_is_loaded_in_component_value($objSpecs, $formPositions)
	{
		Obj::unguard();
		$obj = Obj::create($objSpecs); 

		$form = new _SelectOptionsFromForm(1);

		foreach ($formPositions as $index) {
			$this->assertEquals(1, $form->components[$index]->value);
		}
	}

	private function assert_many_relations_are_loaded_in_component_value($relation, $formPositions)
	{
		//creating unordered files and names before and after m for filtering
		$file1 = factory(File::class)->create(['name' => 'q.jpg']);
		$file2 = factory(File::class)->create(['name' => 'a.jpg']);
		$file3 = factory(File::class)->create(['name' => 'l.jpg']);
		
		$obj = Obj::create(); 
		$relation = $relation.'Plain';
		$obj->{$relation}()->sync([$file1->id, $file2->id, $file3->id]);

		$form = new _SelectOptionsFromForm(1);

		//$relationPlain
		$plainIndex = $formPositions[0];
		$this->assertCount(3, $form->components[$plainIndex]->value);
		$this->assertEquals($file1->id, $form->components[$plainIndex]->value[0]);
		$this->assertEquals($file2->id, $form->components[$plainIndex]->value[1]);
		$this->assertEquals($file3->id, $form->components[$plainIndex]->value[2]);

		//$relationOrdered
		$orderedIndex = $formPositions[1];
		$this->assertCount(3, $form->components[$orderedIndex]->value);
		$this->assertEquals($file2->id, $form->components[$orderedIndex]->value[0]);
		$this->assertEquals($file3->id, $form->components[$orderedIndex]->value[1]);
		$this->assertEquals($file1->id, $form->components[$orderedIndex]->value[2]);

		//$relationFiltered
		$filteredIndex = $formPositions[2];
		$this->assertCount(2, $form->components[$filteredIndex]->value);
		$this->assertEquals($file2->id, $form->components[$filteredIndex]->value[0]);
		$this->assertEquals($file3->id, $form->components[$filteredIndex]->value[1]);
	}
}