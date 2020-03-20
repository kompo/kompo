<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\IconText;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\File;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class OptionsTransformationTest extends EnvironmentBoot
{
	/** @test */
	public function array_options_are_transformed_into_label_value_array()
	{	
		$form = new _SelectDirectOptionsForm();

		$this->assert_array_options_are_transformed_into_label_value_array($form->options(), $form->components[0]->options);
		$this->assert_array_options_are_transformed_into_label_value_array($form->options(), $form->components[1]->options);
	}

	/** @test */
	public function plucked_options_are_transformed_into_label_value_array()
	{			
		factory(Tag::class, 6)->create();	
		$form = new _SelectDirectOptionsForm();

		$this->assert_array_options_are_transformed_into_label_value_array(Tag::pluck('name', 'id'), $form->components[2]->options);
		$this->assert_array_options_are_transformed_into_label_value_array(Tag::pluck('name', 'id'), $form->components[3]->options);
	}

	/** @test */
	public function custom_label_options_are_transformed_into_label_value_array()
	{
		$form = new _SelectDirectOptionsForm();

		$options = $form->components[4]->options;

		$this->assertCount(5, $options);
		$this->assertTrue($options[0]['label'] instanceOf IconText);
    	$this->assertEquals(1, $options[0]['value']);
    	$this->assertEquals('Option 1', $options[0]['label']->components['text']);
    	$this->assertEquals(4, $options[3]['value']);
    	$this->assertEquals('Option 4', $options[3]['label']->components['text']);
	}

	/** @test */
	public function options_are_loaded_from_relationship_with_optionsFrom()
	{
		factory(User::class, 4)->create();
		$users = User::get();
		$usersOrdered = User::orderBy('name')->get();
		$usersFiltered = User::where('name', '<', 'm')->get();
		$files = factory(File::class, 3)->create();
		$filesOrdered = File::orderBy('name')->get();
		$filesFiltered = File::where('name', '<', 'm')->get();

		$form = new _SelectOptionsFromForm();

		$opt = function($index) use($form){
			return $form->components[$index]->options;
		};

    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($users, $opt(0));
    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($usersOrdered, $opt(1));
    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($usersFiltered, $opt(2));
    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($files, $opt(3));
    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($filesOrdered, $opt(4));
    	$this->assert_relationships_options_are_loaded_ordered_or_filtered($filesFiltered, $opt(5));
	}
	


	/** ------------------ PRIVATE --------------------------- */   

	private function assert_array_options_are_transformed_into_label_value_array($opts0, $opts1)
	{
		$this->assertEquals(count($opts0), count($opts1));

		foreach ($opts1 as $key => $opt) {
			$this->assertEquals($key + 1, $opt['value']);
			$this->assertEquals($opts0[$key + 1], $opt['label']);
		}
	}

	private function assert_relationships_options_are_loaded_ordered_or_filtered($opts0, $opts1)
	{
		$this->assertEquals(count($opts0), count($opts1));

		foreach ($opts1 as $key => $opt) {
			$this->assertEquals($opts0[$key]->id, $opt['value']);
			$this->assertEquals($opts0[$key]->name, $opt['label']);
		}
	}
}