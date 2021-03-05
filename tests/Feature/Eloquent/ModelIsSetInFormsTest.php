<?php

namespace Kompo\Tests\Feature\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Post;

class ModelIsSetInFormsTest extends EnvironmentBoot
{
    /** @test */
    public function form_has_new_model_after_boot()
    {
        $form = new _PostForm();

        $this->assertTrue($form->model() instanceof Model);
        $this->assertNull($form->model()->id);
        $this->assertNull($form->modelKey());
    }

    /** @test */
    public function form_has_exisiting_model_after_boot()
    {
        $post = factory(Post::class, 1)->create()->first();
        $form = new _PostForm(1);

        $this->assertTrue($form->model() instanceof Model);
        $this->assertEquals($form->model()->id, $post->id);
        $this->assertEquals($form->model()->title, $post->title);
    }

    /** @test */
    public function form_has_new_model_set_in_created_phase()
    {
        $form = new _ModelInCreatedForm();

        $this->assertTrue($form->model() instanceof Model);
        $this->assertNull($form->model()->id);
        $this->assertNull($form->modelKey());
    }

    /** @test */
    public function form_has_existing_model_set_in_created_phase()
    {
        $post = factory(Post::class, 1)->create()->first();
        $form = new _ModelInCreatedForm(1);

        $this->assertTrue($form->model() instanceof Model);
        $this->assertEquals($form->model()->id, $post->id);
        $this->assertEquals($form->model()->title, $post->title);
    }
}
