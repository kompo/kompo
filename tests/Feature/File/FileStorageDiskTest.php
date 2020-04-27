<?php

namespace Kompo\Tests\Feature\File;

use Illuminate\Support\Facades\Storage;

class FileStorageDiskTest extends FileEnvironmentBoot
{
    public $modelPath = 'sqlite/files';

    /** @test */
    public function file_upload_is_saved_to_disk_then_unlinked()
    {	
        //First Submit to empty form
        $this->submit(
            $form = new _FileStorageDiskForm(), 
            ['path' => ($file1 = $this->createFile() )]
        )->assertStatus(201)
        ->assertJson([
            'path' => $this->file_to_array($file1, 'path', true)
        ]);

        $this->assert_in_storage($file1, 'path', 'my-disk');

        //Update file and delete old one
        $this->submit(
            $form = new _FileStorageDiskForm(1), 
            ['path' => ( $file2 = $this->createFile() )]
        )->assertStatus(200)
        ->assertJson([
            'path' => $this->file_to_array($file2, 'path', true)
        ]);

        $this->assert_not_in_storage($file1, 'path', 'my-disk');
        $this->assert_in_storage($file2, 'path', 'my-disk');

        //Delete file
        $this->submit(
            $form = new _FileStorageDiskForm(1), 
            ['path' => null]
        )->assertStatus(200)
        ->assertJson([
            'path' => null
        ]);

        $this->assert_not_in_storage($file1, 'path', 'my-disk');
        $this->assert_not_in_storage($file2, 'path', 'my-disk');
    }

}