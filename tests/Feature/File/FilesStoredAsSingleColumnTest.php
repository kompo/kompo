<?php

namespace Kompo\Tests\Feature\File;

class FilesStoredAsSingleColumnTest extends FileEnvironmentBoot
{
    public $modelPath = 'sqlite/objs';

    /** @test */
    public function file_upload_save_to_single_column()
    {	
	    //First Submit to empty form
        $this->submit(
        	$form = new _FilesStoredAsSingleColumnForm(), 
        	[
        		'file' => ( $file1 = $this->createFile() ),
        		'file_cast' => ( $file2 = $this->createFile() ),
        		'files' => [$file3 = $this->createFile(), $file4 = $this->createFile()],
        		'files_cast' => [$file5 = $this->createFile()]
        	]
        )->assertStatus(201)
        ->assertJson([
        	'file' => $this->file_to_array($file1, 'file', true),
        	'file_cast' => $this->file_to_array($file2, 'file_cast', true),
        	'files' => [$this->file_to_array($file3, 'files', true), $this->file_to_array($file4, 'files', true)],
        	'files_cast' => [$this->file_to_array($file5, 'files_cast', true)]
        ]);

	    $this->assert_in_storage($file1, 'file');
	    $this->assert_in_storage($file2, 'file_cast');
	    $this->assert_in_storage($file3, 'files');
	    $this->assert_in_storage($file4, 'files');
	    $this->assert_in_storage($file5, 'files_cast');

        $this->assertDatabaseHas('objs', [
	    	'file' => $this->file_to_json($file1, 'file', true),
        	'file_cast' => $this->file_to_json($file2, 'file_cast', true),
        	'files' => $this->files_to_json([$file3, $file4], 'files', true),
        	'files_cast' => $this->files_to_json([$file5], 'files_cast', true),
		]);

        //Reload
        $form = new _FilesStoredAsSingleColumnForm(1);
        $this->assertSubset($this->file_to_array($file1, 'file'), $form->components[0]->value);
        $this->assertSubset($this->file_to_array($file2, 'file_cast'), $form->components[1]->value);
        $this->assertSubset($this->file_to_array($file3, 'files'), $form->components[2]->value[0]);
        $this->assertSubset($this->file_to_array($file4, 'files'), $form->components[2]->value[1]);
        $this->assertSubset($this->file_to_array($file5, 'files_cast'), $form->components[3]->value[0]);

		//Update files
        $this->submit(
        	$form = new _FilesStoredAsSingleColumnForm(1), 
        	[
        		'file' => ( $file6 = $this->createFile() ),
        		'file_cast' => ( $file7 = $this->createFile() ),
        		'files' => [$file8 = $this->createFile()],
        		'files_cast' => [$file5, $file9 = $this->createFile()]
        	]
        )->assertStatus(200)
        ->assertJson([
        	'file' => $this->file_to_array($file6, 'file', true),
        	'file_cast' => $this->file_to_array($file7, 'file_cast', true),
        	'files' => [$this->file_to_array($file8, 'files', true)],
        	'files_cast' => [$this->file_to_array($file5, 'files_cast', true), $this->file_to_array($file9, 'files_cast', true)]
        ]);

	    $this->assert_not_in_storage($file1, 'file');
	    $this->assert_in_storage($file6, 'file');
	    $this->assert_not_in_storage($file2, 'file_cast');
	    $this->assert_in_storage($file7, 'file_cast');
	    $this->assert_not_in_storage($file3, 'files');
	    $this->assert_not_in_storage($file4, 'files');
	    $this->assert_in_storage($file8, 'files');
	    $this->assert_in_storage($file5, 'files_cast');
	    $this->assert_in_storage($file9, 'files_cast');

	    $this->assertDatabaseHas('objs', [
	    	'file' => $this->file_to_json($file6, 'file', true),
        	'file_cast' => $this->file_to_json($file7, 'file_cast', true),
        	'files' => $this->files_to_json([$file8], 'files', true),
        	'files_cast' => $this->files_to_json([$file5, $file9], 'files_cast', true),
		]);

        //Reload
        $form = new _FilesStoredAsSingleColumnForm(1);
        $this->assertSubset($this->file_to_array($file6, 'file'), $form->components[0]->value);
        $this->assertSubset($this->file_to_array($file7, 'file_cast'), $form->components[1]->value);
        $this->assertSubset($this->file_to_array($file8, 'files'), $form->components[2]->value[0]);
        $this->assertSubset($this->file_to_array($file5, 'files_cast'), $form->components[3]->value[0]);
        $this->assertSubset($this->file_to_array($file9, 'files_cast'), $form->components[3]->value[1]);

		//Remove some files
        $this->submit(
        	$form = new _FilesStoredAsSingleColumnForm(1), 
        	[
        		'file' => null,
        		'file_cast' => null,
        		'files' => [],
        		'files_cast' => [$file9]
        	]
        )->assertStatus(200)
        ->assertJson([
        	'file' => [],
        	'file_cast' => [],
        	'files' => [],
        	'files_cast' => [$this->file_to_array($file9, 'files_cast', true)]
        ]);

	    $this->assert_not_in_storage($file6, 'file');
	    $this->assert_not_in_storage($file7, 'file_cast');
	    $this->assert_not_in_storage($file8, 'files');
	    $this->assert_not_in_storage($file5, 'files_cast');
	    $this->assert_in_storage($file9, 'files_cast');

	    $this->assertDatabaseHas('objs', [
	    	'file' => null,
        	'file_cast' => null,
        	'files' => null,
        	'files_cast' => $this->files_to_json([$file9], 'files_cast', true),
		]);

        //Reload
        $form = new _FilesStoredAsSingleColumnForm(1);
        $this->assertNull($form->components[0]->value);
        $this->assertNull($form->components[1]->value);
        $this->assertNull($form->components[2]->value);
        $this->assertSubset($this->file_to_array($file9, 'files_cast'), $form->components[3]->value[0]);
    }

}