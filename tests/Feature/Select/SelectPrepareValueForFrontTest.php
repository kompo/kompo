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
		$form = new _SelectDirectOptionsForm(1);

		$this->assertEquals(1, $form->components[0]->value);
		$this->assertCount(2, $form->components[1]->value);
		$this->assertEquals(3, $form->components[1]->value[0]);
		$this->assertEquals(4, $form->components[1]->value[1]);
		$this->assertEquals(2, $form->components[3]->value[0]);
		$this->assertEquals(5, $form->components[3]->value[1]);

		$this->assertEquals(1, $form->components[2]->value);
		$this->assertCount(2, $form->components[3]->value);
		$this->assertEquals(1, $form->components[4]->value);
		$this->assertCount(2, $form->components[5]->value);
		$this->assertEquals(3, $form->components[5]->value[0]);
		$this->assertEquals(4, $form->components[5]->value[1]);
	}

	/** @test */
	public function expected_value_is_plucked_from_relationships()
	{
		$obj = Obj::create([
			'user_id' => 1
		]);

		factory(User::class, 4)->create();
		$users = User::get();
		$files = factory(File::class, 3)->create();

		$obj->belongsToManyPlain()->attach([$files[0]->id, $files[2]->id]);

		$form = new _SelectOptionsFromForm(1);

		$this->assertEquals(1, $form->components[0]->value);
		$this->assertCount(2, $form->components[3]->value);
		$this->assertEquals($files[0]->id, $form->components[3]->value[0]);
		$this->assertEquals($files[2]->id, $form->components[3]->value[1]);

	}
}