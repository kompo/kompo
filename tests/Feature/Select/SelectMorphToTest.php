<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\Models\User;

class SelectMorphToTest extends SelectEnvironmentBootOneTest
{
    /** @test */
    public function select_works_with_morph_to_plain_crud()
    {
    	$this->assert_crud_one_selects(_SelectMorphToForm::class, 'morphToPlain', 'morph_to_plain');
    }

    /** @test */
    public function select_works_with_morph_to_ordered_crud()
    {
    	$this->assert_crud_one_selects(_SelectMorphToForm::class, 'morphToOrdered', 'morph_to_ordered');
    }
    
    /** @test */
    public function select_works_with_morph_to_filtered_crud()
    {
    	$this->assert_crud_one_selects(_SelectMorphToForm::class, 'morphToFiltered', 'morph_to_filtered');
    }


    /** ------------------ PRIVATE --------------------------- */ 

    protected function assert_database_has_expected_row($user)
    {
        $this->assertDatabaseHas('objs', [
            'model_id' => $user->id,
            'model_type' => User::class
        ]);
    }

    protected function assert_database_missing_expected_row($user)
    {
        $this->assertDatabaseMissing('objs', [
            'model_type' => User::class
        ]);
    }

}