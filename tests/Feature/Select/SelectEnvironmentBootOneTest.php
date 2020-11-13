<?php

namespace Kompo\Tests\Feature\Select;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Models\User;

abstract class SelectEnvironmentBootOneTest extends EnvironmentBoot
{
    abstract protected function assert_database_has_expected_row($user);
    abstract protected function assert_database_missing_expected_row($user);

    protected $currentRelation;

    protected $currentForm;

    protected function assert_crud_one_selects($form, $relation, $snaked)
    {   
        $this->currentRelation = $relation;
        $this->currentForm = $form;

        $type = substr($relation, -7) == 'Ordered' ? 'ordered' : (substr($relation, -8) == 'Filtered' ? 'filtered' : '');

        //Insert
        $user1 = User::first();
        $this->submit(
            $form = $this->getForm(), [
                $relation => $user1->id
            ]
        )->assertStatus(201)
        ->assertJson([
            $snaked => array_merge($user1->toArray(), ['order' => $type == 'filtered' ? 1 : null])
        ]);

        $this->assert_database_has_expected_row($user1);
        if($type == 'filtered')
            $this->assertEquals(1, $user1->fresh()->order);

        //Reload
        $form = $this->getForm(1);
        $this->assertEquals($user1->id, $form->komponents[0]->value);

        //Update
        $user2 = factory(User::class)->create();
        $this->submit(
            $form = $this->getForm(1), [
                $relation => $user2->id
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => array_merge($user2->toArray(), ['order' => $type == 'filtered' ? 1 : null])
        ]);

        $this->assert_database_has_expected_row($user2);
        if($type == 'filtered')
            $this->assertEquals(1, $user2->fresh()->order);

        //Reload
        $form = $this->getForm(1);
        $this->assertEquals($user2->id, $form->komponents[0]->value);

        //Remove
        $this->submit(
            $form = $this->getForm(1), [
                $relation => null
            ]
        )->assertStatus(200)
        ->assertJson([
            $snaked => null
        ]);

        $this->assert_database_missing_expected_row($user2);
        $this->assertEquals(2, \DB::table('users')->count());

        //Reload
        $form = $this->getForm(1);
        $this->assertNull($form->komponents[0]->value);
    }

    protected function getForm($id = null)
    {
        $form = $this->currentForm;

        return new $form($id, [
            'komponent' => $this->currentRelation
        ]);
    }

}