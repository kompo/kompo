<?php

namespace Kompo\Tests\Feature\Place;

class PlaceAttributesToColumnsTest extends PlaceEnvironmentBoot
{
    /** @test */
    public function place_has_attributes_stored_in_different_columns()
    {
        //First Submit to empty form
        $this->submit(
            $form = _PlaceAttributesToColumnsForm::boot(),
            ['address' => [$place1 = $this->createPlace()]]
        )->assertStatus(201)
        ->assertJson($this->place_to_array($place1));

        $this->assertDatabaseHas('places', $this->place_to_array($place1));

        //Display previously saved
        $form = _PlaceAttributesToColumnsForm::boot(1);

        $this->assertIsArray($newValue = $form->komponents[0]->value);
        $this->assertSubset($newValue, $this->place_to_array($place1));

        //Update file
        $this->submit(
            $form = _PlaceAttributesToColumnsForm::boot(1),
            ['address' => [$place2 = $this->createPlace()]]
        )->assertStatus(200)
        ->assertJson($this->place_to_array($place2));

        $this->assertDatabaseHas('places', $this->place_to_array($place2));

        //Remove file
        $this->submit(
            $form = _PlaceAttributesToColumnsForm::boot(1),
            ['address' => null]
        )->assertStatus(200)
        ->assertJsonMissing($this->place_to_array($place2));

        $this->assertDatabaseMissing('places', $this->place_to_array($place2));
    }

    /** @test */
    public function place_stored_in_different_columns_with_extra_attributes()
    {
        $place1 = $this->createPlace();
        $expected = array_merge(
            $this->place_to_array($place1),
            ['all_columns' => 'some-constant']
        );
        $this->submit(
            $form = _PlaceAttributesToColumnsForm::boot(null, ['withExtraAttributes' => true]),
            ['address' => [$place1]]
        )->assertStatus(201)
        ->assertJson($expected);

        $this->assertDatabaseHas('places', $expected);
    }
}
