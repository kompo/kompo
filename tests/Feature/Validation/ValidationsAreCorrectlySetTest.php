<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Tests\EnvironmentBoot;

class ValidationsAreCorrectlySetTest extends EnvironmentBoot
{
    /** @test */
    public function validations_are_correctly_set_in_forms()
    {
        $rules = (new _SetValidationsForm())->config('rules');

        $this->assertIsArray($rules);
        $this->assertCount(7, $rules);
        foreach ($rules as $attribute => $validations) {
            $this->assertIsArray($validations);
        }
        $this->assertCount(2, $rules['name1']);
        $this->assertCount(3, $rules['name2']);
        $this->assertCount(4, $rules['name3']);
        $this->assertCount(2, $rules['name4']);
        $this->assertArrayNotHasKey('name5', $rules);
        $this->assertCount(2, $rules['name6']);
        $this->assertCount(3, $rules['name7']);
        $this->assertCount(1, $rules['other-name']);
    }
}
