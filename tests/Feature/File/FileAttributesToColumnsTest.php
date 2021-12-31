<?php

namespace Kompo\Tests\Feature\File;

class FileAttributesToColumnsTest extends FileEnvironmentBoot
{
    public $modelPath = 'sqlite/files';

    /** @test */
    public function file_has_attributes_stored_in_different_columns()
    {
        //First Submit to empty form
        $this->submit(
            $form = _FileAttributesToColumnsForm::boot(),
            ['path' => ($file1 = $this->createFile())]
        )->assertStatus(201)
        ->assertJson($this->file_to_array($file1, 'path'));

        $this->assert_in_storage($file1, 'path');
        $this->assertDatabaseHas('files', $this->file_to_array($file1, 'path'));

        //Display previously saved
        $form = _FileAttributesToColumnsForm::boot(1);

        $this->assertIsArray($newValue = $form->elements[0]->value);
        $this->assertSubset($newValue, $this->file_to_array($file1, 'path'));

        //Update file
        $this->submit(
            $form = _FileAttributesToColumnsForm::boot(1),
            ['path' => ($file2 = $this->createFile('smthelse.png', 12))]
        )->assertStatus(200)
        ->assertJson($this->file_to_array($file2, 'path'));

        $this->assert_not_in_storage($file1, 'path');
        $this->assert_in_storage($file2, 'path');
        $this->assertDatabaseHas('files', $this->file_to_array($file2, 'path'));

        //Remove file
        $this->submit(
            $form = _FileAttributesToColumnsForm::boot(1),
            ['path' => null]
        )->assertStatus(200)
        ->assertJsonMissing($this->file_to_array($file2, 'path'));

        $this->assert_not_in_storage($file2, 'path');
        $this->assertDatabaseMissing('files', $this->file_to_array($file2, 'path'));
    }

    /** @test */
    public function file_stored_in_different_columns_with_extra_attributes()
    {
        $file1 = $this->createFile();
        $expected = array_merge(
            $this->file_to_array($file1, 'path'),
            ['all_columns' => 'some-constant']
        );
        $this->submit(
            $form = _FileAttributesToColumnsForm::boot(null, ['withExtraAttributes' => true]),
            ['path' => $file1]
        )->assertStatus(201)
        ->assertJson($expected);

        $this->assert_in_storage($file1, 'path');
        $this->assertDatabaseHas('files', $expected);
    }
}
