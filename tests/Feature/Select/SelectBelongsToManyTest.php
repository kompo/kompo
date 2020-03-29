<?php

namespace Kompo\Tests\Feature\Select;

class SelectBelongsToManyTest extends SelectEnvironmentBootManyTest
{

    /** @test */
    public function select_works_with_belongs_to_many_plain_crud()
    {
    	$this->assert_crud_many_selects(_SelectBelongsToManyForm::class, 'belongsToManyPlain', 'belongs_to_many_plain', 0);
    }
    
    /** @test */
    public function select_works_with_belongs_to_many_ordered_crud()
    {
    	$this->assert_crud_many_selects(_SelectBelongsToManyForm::class, 'belongsToManyOrdered', 'belongs_to_many_ordered', 1);
    }
    
    /** @test */
    public function select_works_with_belongs_to_many_filtered_crud()
    {
    	$this->assert_crud_many_selects(_SelectBelongsToManyForm::class, 'belongsToManyFiltered', 'belongs_to_many_filtered', 2);
    }


    /** ------------------ PRIVATE --------------------------- */ 
    protected function assert_database_has_expected_row($file, $type = null)
    {
        return $this->assertDatabaseHas('file_obj', [
            'obj_id' => 1,
            'file_id' => $file->id,
            'order' => $type == 'filtered' ? 1 : null
        ]);
    }

}