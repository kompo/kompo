<?php

namespace Kompo\Tests\Feature\Place;

class PlacesStoredAsSingleColumnTest extends PlaceEnvironmentBoot
{
    /** @test */
    public function place_upload_save_to_single_column()
    {
        //First Submit to empty form
        $this->submit(
            $form = _PlacesStoredAsSingleColumnForm::boot(),
            [
                'place'       => [$place1 = $this->createPlace()],
                'place_cast'  => [$place2 = $this->createPlace()],
                'places'      => [$place3 = $this->createPlace(), $place4 = $this->createPlace()],
                'places_cast' => [$place5 = $this->createPlace()],
            ]
        )->assertStatus(201)
        ->assertJson([
            'place'       => $this->place_to_array($place1),
            'place_cast'  => $this->place_to_array($place2),
            'places'      => [$this->place_to_array($place3), $this->place_to_array($place4)],
            'places_cast' => [$this->place_to_array($place5)],
        ]);

        $this->assertDatabaseHas('objs', [
            'place'       => $this->place_to_json($place1),
            'place_cast'  => $this->place_to_json($place2),
            'places'      => $this->places_to_json([$place3, $place4]),
            'places_cast' => $this->places_to_json([$place5]),
        ]);

        //Reload
        $form = _PlacesStoredAsSingleColumnForm::boot(1);
        $this->assertSubset($this->place_to_array($place1), $form->komponents[0]->value);
        $this->assertSubset($this->place_to_array($place2), $form->komponents[1]->value);
        $this->assertSubset($this->place_to_array($place3), $form->komponents[2]->value[0]);
        $this->assertSubset($this->place_to_array($place4), $form->komponents[2]->value[1]);
        $this->assertSubset($this->place_to_array($place5), $form->komponents[3]->value[0]);

        //Update places
        $this->submit(
            $form = _PlacesStoredAsSingleColumnForm::boot(1),
            [
                'place'       => [$place6 = $this->createPlace()],
                'place_cast'  => [$place7 = $this->createPlace()],
                'places'      => [$place8 = $this->createPlace()],
                'places_cast' => [$place5, $place9 = $this->createPlace()],
            ]
        )->assertStatus(200)
        ->assertJson([
            'place'       => $this->place_to_array($place6),
            'place_cast'  => $this->place_to_array($place7),
            'places'      => [$this->place_to_array($place8)],
            'places_cast' => [$this->place_to_array($place5), $this->place_to_array($place9)],
        ]);

        $this->assertDatabaseHas('objs', [
            'place'       => $this->place_to_json($place6),
            'place_cast'  => $this->place_to_json($place7),
            'places'      => $this->places_to_json([$place8]),
            'places_cast' => $this->places_to_json([$place5, $place9]),
        ]);

        //Reload
        $form = _PlacesStoredAsSingleColumnForm::boot(1);
        $this->assertSubset($this->place_to_array($place6), $form->komponents[0]->value);
        $this->assertSubset($this->place_to_array($place7), $form->komponents[1]->value);
        $this->assertSubset($this->place_to_array($place8), $form->komponents[2]->value[0]);
        $this->assertSubset($this->place_to_array($place5), $form->komponents[3]->value[0]);
        $this->assertSubset($this->place_to_array($place9), $form->komponents[3]->value[1]);

        //Remove some places
        $this->submit(
            $form = _PlacesStoredAsSingleColumnForm::boot(1),
            [
                'place'       => null,
                'place_cast'  => null,
                'places'      => [],
                'places_cast' => [$place9],
            ]
        )->assertStatus(200)
        ->assertJson([
            'place'       => [],
            'place_cast'  => [],
            'places'      => [],
            'places_cast' => [$this->place_to_array($place9)],
        ]);

        $this->assertDatabaseHas('objs', [
            'place'       => null,
            'place_cast'  => null,
            'places'      => null,
            'places_cast' => $this->places_to_json([$place9]),
        ]);

        //Reload
        $form = _PlacesStoredAsSingleColumnForm::boot(1);
        $this->assertNull($form->komponents[0]->value);
        $this->assertNull($form->komponents[1]->value);
        $this->assertNull($form->komponents[2]->value);
        $this->assertSubset($this->place_to_array($place9), $form->komponents[3]->value[0]);
    }
}
