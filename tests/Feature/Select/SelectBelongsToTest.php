<?php

namespace Kompo\Tests\Feature\Select;

class SelectBelongsToTest extends SelectEnvironmentBootOneTest
{
    /** @test */
    public function select_works_with_belongs_to_plain_crud()
    {
        $this->assert_crud_one_selects(_SelectBelongsToForm::class, 'belongsToPlain', 'belongs_to_plain');
    }

    /** @test */
    public function select_works_with_belongs_to_ordered_crud()
    {
        $this->assert_crud_one_selects(_SelectBelongsToForm::class, 'belongsToOrdered', 'belongs_to_ordered');
    }

    /** @test */
    public function select_works_with_belongs_to_filtered_crud()
    {
        $this->assert_crud_one_selects(_SelectBelongsToForm::class, 'belongsToFiltered', 'belongs_to_filtered');
    }

    /** ------------------ PROTECTED --------------------------- */
    protected function assert_database_has_expected_row($user)
    {
        $this->assertDatabaseHas('objs', [
            'user_id' => $user->id,
        ]);
    }

    protected function assert_database_missing_expected_row($user)
    {
        $this->assertDatabaseMissing('objs', [
            'user_id' => $user->id,
        ]);
    }
}
