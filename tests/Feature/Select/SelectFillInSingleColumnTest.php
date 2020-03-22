<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\EnvironmentBoot;

class SelectFillInSingleColumnTest extends EnvironmentBoot
{
    /** @test */
    public function select_option_filled_in_single_column()
    {	
	    //First Submit to empty form
        $this->submit(
        	$form = new _SelectAttributeFillsForm(), [
                'tag' => 1,
                'tags' => [3,4],
                'tags_cast' => [2,5]
        ])->assertStatus(201)
        ->assertJson([
            'tag' => 1,
            'tags' => [3,4],
            'tags_cast' => [2,5]
        ]);

        $this->assertDatabaseHas('objs', [
	    	'tag' => 1,
        	'tags' => json_encode([3,4]),
        	'tags_cast' => json_encode([2,5]),
		]);

        //Reload
        $form = new _SelectAttributeFillsForm(1);
        $this->assertEquals(1, $form->components[0]->value);
        $this->assertCount(2, $form->components[1]->value);
        $this->assertSubset([3,4], $form->components[1]->value);
        $this->assertCount(2, $form->components[2]->value);
        $this->assertSubset([2,5], $form->components[2]->value);

		//Update files
        $this->submit(
        	$form = new _SelectAttributeFillsForm(1), [
                'tag' => 6,
                'tags' => [13,'string'],
                'tags_cast' => [22]
        ])->assertStatus(200)
        ->assertJson([
            'tag' => 6,
            'tags' => [13,'string'],
            'tags_cast' => [22]
        ]);

	    $this->assertDatabaseHas('objs', [
            'tag' => 6,
            'tags' => json_encode([13,'string']),
            'tags_cast' => json_encode([22]),
		]);

        //Reload
        $form = new _SelectAttributeFillsForm(1);
        $this->assertEquals(6, $form->components[0]->value);
        $this->assertCount(2, $form->components[1]->value);
        $this->assertSubset([13,'string'], $form->components[1]->value);
        $this->assertCount(1, $form->components[2]->value);
        $this->assertSubset([22], $form->components[2]->value);

		//Remove some files
        $this->submit(
        	$form = new _SelectAttributeFillsForm(1), [
                'tag' => null,
                'tags' => null,
                'tags_cast' => null
        ])->assertStatus(200)
        ->assertJson([
            'tag' => null,
            'tags' => null,
            'tags_cast' => null
        ]);

	    $this->assertDatabaseHas('objs', [
	    	'tag' => null,
        	'tags' => null,
        	'tags_cast' => null
		]);

        //Reload
        $form = new _SelectAttributeFillsForm(1);
        $this->assertNull($form->components[0]->value);
        $this->assertNull($form->components[1]->value);
        $this->assertNull($form->components[2]->value);
    }

}