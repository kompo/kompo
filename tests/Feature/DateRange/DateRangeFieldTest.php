<?php

namespace Kompo\Tests\Feature\DateRange;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\Post;

class DateRangeFieldTest extends EnvironmentBoot
{
	/** @test */
	public function date_range_is_assigned_value_in_handled_form()
	{
		$form = new _DateRangeHandledForm();

		$this->assertNull($form->komponents[0]->value);
		$this->assertCount(2, $form->komponents[1]->value);
		$this->assertEquals(date('Y-m-d'), $form->komponents[1]->value[0]);
		$this->assertEquals(date('Y-m-d', strtotime('+1 days')), $form->komponents[1]->value[1]);
	}

	/** @test */
	public function date_range_is_loaded_a_value_in_eloquent_form()
	{
		$post = factory(Post::class, 1)->create()->first();

		$form = new _DateRangeValueFromModelForm(1);

		$this->assertIsArray($form->komponents[0]->value);
		$this->assertEquals($post->created_at, $form->komponents[0]->value[0]);
		$this->assertEquals($post->updated_at, $form->komponents[0]->value[1]);
	}

	/** @test */
	public function date_range_is_loaded_and_saved_as_attribute()
	{
		$form = new _DateRangeAttributeForm();

		$this->assertNull($form->komponents[0]->value);
		$this->assertCount(2, $form->komponents[1]->value);
		$this->assertEquals(date('Y-m-d'), $form->komponents[1]->value[0]);
		$this->assertEquals(date('Y-m-d', strtotime('+1 days')), $form->komponents[1]->value[1]);

		$this->assertDatabaseMissing('objs', ['id' => 1]);

		$start_d = date('Y-m-d', strtotime('+2 days'));
		$end_d = date('Y-m-d', strtotime('+3 days'));
		$start_dt = date('Y-m-d H:i', strtotime('+4 days'));
		$end_dt = date('Y-m-d H:i', strtotime('+5 days'));

		$this->assert_date_range_fields_save_as_attribute($form, 201, $start_d, $end_d, $start_dt, $end_dt);

		$start_d = date('Y-m-d', strtotime('+6 days'));
		$end_d = null;
		$start_dt = date('Y-m-d H:i', strtotime('+7 days'));
		$end_dt = null;

		$this->assert_date_range_fields_save_as_attribute(new _DateRangeAttributeForm(1), 200, $start_d, $end_d, $start_dt, $end_dt);
	}

	/** @test */
	public function date_range_is_loaded_and_saved_as_relation()
	{
		$form = new _DateRangeRelationForm();

		$this->assertNull($form->komponents[0]->value);
		$this->assertCount(2, $form->komponents[1]->value);
		$this->assertEquals(date('Y-m-d'), $form->komponents[1]->value[0]);
		$this->assertEquals(date('Y-m-d', strtotime('+1 days')), $form->komponents[1]->value[1]);

		$this->assertDatabaseMissing('posts', ['id' => 1]);
		$this->assertDatabaseMissing('objs', ['id' => 1]);

		$start_d = date('Y-m-d', strtotime('+2 days'));
		$end_d = date('Y-m-d', strtotime('+3 days'));
		$start_dt = date('Y-m-d H:i', strtotime('+4 days'));
		$end_dt = date('Y-m-d H:i', strtotime('+5 days'));

		$this->assert_date_range_fields_save_as_relation($form, 201, $start_d, $end_d, $start_dt, $end_dt);
		
		$start_d = date('Y-m-d', strtotime('+6 days'));
		$end_d = null;
		$start_dt = date('Y-m-d', strtotime('+7 days'));
		$end_dt = null;

		$this->assert_date_range_fields_save_as_relation(new _DateRangeRelationForm(1), 200, $start_d, $end_d, $start_dt, $end_dt);

	}


	/************* PRIVATE ***********************/
	private function assert_date_range_fields_save_as_attribute($form, $status, $start_d, $end_d, $start_dt, $end_dt)
	{
		$this->submit($form, [
			'start_date' => $start_d,
			'end_date' => $end_d,
			'start_datetime' => $start_dt,
			'end_datetime' => $end_dt
		])->assertStatus($status)
		->assertJson([
			'start_date' => $start_d,
			'end_date' => $end_d,
			'start_datetime' => $start_dt,
			'end_datetime' => $end_dt
		]);

		$this->assertDatabaseHas('objs', [
			'id' => 1,
			'start_date' => $start_d,
			'end_date' => $end_d,
			'start_datetime' => $start_dt,
			'end_datetime' => $end_dt
		]);
		$this->assertDatabaseMissing('objs', ['id' => 2]);
	}

	private function assert_date_range_fields_save_as_relation($form, $status, $start_d, $end_d, $start_dt, $end_dt)
	{
		$this->submit($form, [
			'obj.start_date' => $start_d,
			'obj.end_date' => $end_d,
			'obj.start_datetime' => $start_dt,
			'obj.end_datetime' => $end_dt
		])->assertStatus($status)
		->assertJson([
			'obj' => [
				'start_date' => $start_d,
				'end_date' => $end_d,
				'start_datetime' => $start_dt,
				'end_datetime' => $end_dt
			]
		]);

		$this->assertDatabaseHas('posts', [
			'id' => 1
		]);

		$this->assertDatabaseHas('objs', [
			'id' => 1,
			'start_date' => $start_d,
			'end_date' => $end_d,
			'start_datetime' => $start_dt,
			'end_datetime' => $end_dt
		]);

		$this->assertDatabaseMissing('posts', ['id' => 2]);
		$this->assertDatabaseMissing('objs', ['id' => 2]);
	}

}