<?php

namespace Kompo\Tests\Feature\Model;

use Kompo\Tests\EnvironmentBoot;

class AssigningCreatedUpdatedByTest extends EnvironmentBoot
{
    /** @test */
    public function no_error_is_thrown_if_not_authenticated()
    {
        $this->submit_kompo_model_for_created_updated_by(new _AssigningCreatedUpdatedByForm());

        $this->assertDatabaseHas('kompo_models', [
            'name'              => 'test-input',
            'created_by'        => null,
            'updated_by'        => null,
            'custom_created_by' => null,
            'custom_updated_by' => null,
        ]);
    }

    /** @test */
    public function created_updated_by_assigned_correctly()
    {
        $this->actingAs($this->user)->submit_kompo_model_for_created_updated_by(new _AssigningCreatedUpdatedByForm());

        $this->assertDatabaseHas('kompo_models', [
            'name'              => 'test-input',
            'created_by'        => 1,
            'updated_by'        => 1,
            'custom_created_by' => null,
            'custom_updated_by' => null,
        ]);
    }

    /** @test */
    public function custom_created_updated_by_assigned_correctly()
    {
        $this->actingAs($this->user)->submit_kompo_model_for_created_updated_by(new _AssigningCustomCreatedUpdatedByForm());

        $this->assertDatabaseHas('kompo_models', [
            'name'              => 'test-input',
            'created_by'        => null,
            'updated_by'        => null,
            'custom_created_by' => 1,
            'custom_updated_by' => 1,
        ]);
    }

    /** ------------------ PRIVATE --------------------------- */
    private function submit_kompo_model_for_created_updated_by($form)
    {
        $this->submit($form, [
            'name' => 'test-input',
        ])
            ->assertStatus(201)
            ->assertJson([
                'name' => 'test-input',
            ]);
    }
}
