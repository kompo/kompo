<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\Models\Obj;

class SelectMorphToManyTest extends SelectEnvironmentBootManyTest
{

    /** @test */
    public function select_works_with_morph_to_many_plain_crud()
    {
    	$this->assert_crud_many_selects(_SelectMorphToManyForm::class, 'morphToManyPlain', 'morph_to_many_plain', 0);
    }
    
    /** @test */
    public function select_works_with_morph_to_many_ordered_crud()
    {
    	$this->assert_crud_many_selects(_SelectMorphToManyForm::class, 'morphToManyOrdered', 'morph_to_many_ordered', 1);
    }
    
    /** @test */
    public function select_works_with_morph_to_many_filtered_crud()
    {
    	$this->assert_crud_many_selects(_SelectMorphToManyForm::class, 'morphToManyFiltered', 'morph_to_many_filtered', 2);
    }


    /** ------------------ PRIVATE --------------------------- */ 
    protected function assert_database_has_expected_row($file, $type = null) //overriden
    {
        return $this->assertDatabaseHas('file_obj', [
            'model_id' => 1,
            'model_type' => Obj::class,
            'file_id' => $file->id,
            'order' => $type == 'filtered' ? 1 : null
        ]);
    }

}