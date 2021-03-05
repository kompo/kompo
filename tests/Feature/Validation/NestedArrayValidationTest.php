<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Tests\EnvironmentBoot;

class NestedArrayValidationTest extends EnvironmentBoot
{
    /** @test */
    public function nested_naming_does_not_interfere_with_array_validations()
    {
        $form = new _ArrayFieldNameValidationForm();

        $response = $this->submit($form, [
            'tags' => [
                ['name' => 'tag'],
            ],
        ])->assertStatus(422);

        $this->assertCount(1, $response['errors']);
        $this->assertCount(1, $response['errors']['tags.0.id']);
        $this->assertEquals('is required.', substr($response['errors']['tags.0.id'][0], -12));
    }
}
