<?php

namespace Kompo\Tests\Feature\Field;

use Kompo\Exceptions\NotOneToOneRelationException;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Obj;
use Kompo\Tests\Models\Post;
use Kompo\Tests\Models\Tag;

class FieldNameOneToOneParsingTest extends EnvironmentBoot
{
	/** @test */
	public function nested_has_one_handled_from_parent_model_form()
	{
		$postTitle = 'post-title';
		$objTitle = 'obj-title';
		$objTag = 'obj-tag';
		$postTagTitle = 'postTag-title';

		$this->submit_status_and_json_assertions(new _FieldNameOneToOneParsingForm(), 201, $postTitle, $objTitle, $objTag, $postTagTitle);

		$this->database_and_form_reloading_assertions($postTitle, $objTitle, $objTag, $postTagTitle);

		//Removing one attribute of obj and changing postTag attribute
		$postTitle = 'post-title2';
		$objTitle = 'obj-title2';
		$objTag = null;
		$postTagTitle = 'postTag-title2';

		$this->submit_status_and_json_assertions(new _FieldNameOneToOneParsingForm(1), 200, $postTitle, $objTitle, $objTag, $postTagTitle);

		$this->database_and_form_reloading_assertions($postTitle, $objTitle, $objTag, $postTagTitle);

		//Removing other attribute of obj and deleting postTag
		$postTitle = null;
		$objTitle = null;
		$objTag = 'obj-tag2';
		$postTagTitle = null;

		$this->submit_status_and_json_assertions(new _FieldNameOneToOneParsingForm(1), 200, $postTitle, $objTitle, $objTag, $postTagTitle);

		$this->database_and_form_reloading_assertions($postTitle, $objTitle, $objTag, $postTagTitle);

		//Deleting both attributes of obj
		$postTitle = 'smth';
		$objTitle = null;
		$objTag = null;
		$postTagTitle = null;

		$this->submit_status_and_json_assertions(new _FieldNameOneToOneParsingForm(1), 200, $postTitle, $objTitle, $objTag, $postTagTitle);

		$this->database_and_form_reloading_assertions($postTitle, $objTitle, $objTag, $postTagTitle);
	}
	
	/** @test */
	public function not_one_to_one_relation_in_field_name_throws_exception()
	{
		$tags = factory(Tag::class, 2)->create();
		$post = factory(Post::class, 1)->create()->first();

		$this->expectException(NotOneToOneRelationException::class);

		$form = new _NotOneToOneRelationInFieldNameForm($post->id);
	}


	/** ------------------ PRIVATE --------------------------- */  

	private function submit_status_and_json_assertions($form, $status, $postTitle, $objTitle, $objTag, $postTagTitle)
	{
		$this->submit($form, [
			'title' => $postTitle,
			'obj_title' => $objTitle,
			'obj_tag' => $objTag,
			'postTag_title' => $postTagTitle
		])->assertStatus($status)
		->assertJson([
			'id' => 1,
			'title' => $postTitle,
			'obj' => [
				'title' => $objTitle,
				'tag' => $objTag
			],
			'post_tag' => [
				'title' => $postTagTitle
			]
		]);
	}

	private function database_and_form_reloading_assertions($postTitle, $objTitle, $objTag, $postTagTitle)
	{
		$this->assertEquals(1, \DB::table('posts')->count());
		$this->assertDatabaseHas('posts', ['id' => 1,'title' => $postTitle]);
		if($objTitle || $objTag){
			$this->assertEquals(1, \DB::table('objs')->count());
			$this->assertDatabaseHas('objs', ['id' => 1,'title' => $objTitle, 'tag' => $objTag]);
		}else{
			$this->assertEquals(0, \DB::table('objs')->count());			
		}

		if($postTagTitle){
			$this->assertEquals(1, \DB::table('post_tag')->count());
			$this->assertDatabaseHas('post_tag', ['id' => 1,'title' => $postTagTitle]);
		}else{
			$this->assertEquals(0, \DB::table('post_tag')->count());		
		}

		$form = new _FieldNameOneToOneParsingForm(1);

		$this->assertEquals($objTitle, $form->komponents[1]->value);
		$this->assertEquals($objTag, $form->komponents[2]->value);
		$this->assertEquals($postTagTitle, $form->komponents[3]->value);
	}
	
}