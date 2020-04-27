<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Post;
use Kompo\Tests\Models\Tag;
use Kompo\Tests\Models\User;

class SettingFieldValuesFromDbTest extends EnvironmentBoot
{
	/** @test */
	public function attributes_and_relationships_are_correctly_set_on_fields_and_model()
	{
		$user = factory(User::class, 1)->create()->first();
		$tags = factory(Tag::class, 2)->create();
		$post = factory(Post::class, 1)->create(['user_id' => $user->id])->first();
		$post->tags()->attach([1,2]);

		$this->assertEquals($post->author->id, $user->id);

		$form = new _SettingFieldValuesFromDbForm($post->id);

		$this->assertEquals($post->title, $form->model()->title);
		$this->assertEquals($post->title, $form->komponents[0]->komponents[0]->value);

		$this->assertEquals($post->published_at, $form->model()->published_at);
		$this->assertEquals($post->published_at, $form->komponents[0]->komponents[1]->value);

		$this->assertEquals($tags[0]->name, $form->model()->tags[0]->name);
		$this->assertEquals($tags[0]->name, $form->komponents[1]->value[0]->name);

		$this->assertEquals($tags[1]->name, $form->model()->tags[1]->name);
		$this->assertEquals($tags[1]->name, $form->komponents[1]->value[1]->name);

		$this->assertEquals($post->author->id, $form->model()->author->id);
		$this->assertEquals($post->author->name, $form->komponents[2]->value);

	}

	/** @test */
	public function non_existing_attribute_returns_null()
	{
		$post = factory(Post::class, 1)->create()->first();

		$form = new _NonExistingAttributeInFieldNameForm($post->id);

		$this->assertEquals('fneyaibyveiy', $form->komponents[0]->name);
		$this->assertNull($form->komponents[0]->value);
	}


}