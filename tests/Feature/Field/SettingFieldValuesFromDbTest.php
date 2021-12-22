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
        $post->tags()->attach([1, 2]);

        $this->assertEquals($post->author->id, $user->id);

        $form = _SettingFieldValuesFromDbForm::boot($post->id);

        $this->assertEquals($post->title, $form->model()->title);
        $this->assertEquals($post->title, $form->elements[0]->elements[0]->value);

        $this->assertEquals($post->published_at, $form->model()->published_at);
        $this->assertEquals($post->published_at, $form->elements[0]->elements[1]->value);

        $this->assertEquals($tags[0]->name, $form->model()->tags[0]->name);
        $this->assertEquals($tags[0]->name, $form->elements[1]->value[0]->name);

        $this->assertEquals($tags[1]->name, $form->model()->tags[1]->name);
        $this->assertEquals($tags[1]->name, $form->elements[1]->value[1]->name);

        $this->assertEquals($post->author->id, $form->model()->author->id);
        $this->assertEquals($post->author->name, $form->elements[2]->value);
    }

    /** @test */
    public function non_null_but_nullable_values_are_correctly_set()
    {
        $post = factory(Post::class, 1)->create([
            'title'   => '',
            'integer' => 0,
        ])->first();

        $form = _NullableButNonNullValuesForm::boot($post->id);

        $this->assertSame('', $form->elements[0]->value);
        $this->assertNotSame(null, $form->elements[0]->value);
        $this->assertSame('0', $form->elements[1]->value);
        $this->assertNotSame(null, $form->elements[1]->value);
    }

    /** @test */
    public function non_existing_attribute_returns_null()
    {
        $post = factory(Post::class, 1)->create()->first();

        $form = _NonExistingAttributeInFieldNameForm::boot($post->id);

        $this->assertEquals('fneyaibyveiy', $form->elements[0]->name);
        $this->assertNull($form->elements[0]->value);
    }
}
