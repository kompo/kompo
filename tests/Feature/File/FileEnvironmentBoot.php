<?php

namespace Kompo\Tests\Feature\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Kompo\Tests\EnvironmentBoot;

class FileEnvironmentBoot extends EnvironmentBoot
{
	protected $idKey;
    protected $pathKey;
    protected $nameKey;
    protected $mime_typeKey;
    protected $sizeKey;

    protected function getEnvironmentSetUp($app)
    {
    	parent::getEnvironmentSetUp($app);
    	collect(config('kompo.files_attributes'))->each(function($column, $key){
            $this->{$key.'Key'} = $column;
        });
    }

	protected function createFile($name = null, $sizeKb = 1)
	{
		return UploadedFile::fake()->create($name ?: 'file'.rand(1000,9999).'.pdf', $sizeKb);
	}

	protected function file_to_array($file, $column, $withHash = false)
	{
		return array_merge([
			$this->nameKey => $file->name,
			$this->pathKey => 'storage/'.$this->modelPath.'/'.$column.'/'.$file->hashName(),
			$this->mime_typeKey => $file->getClientMimeType(),
			$this->sizeKey => $file->getSize()
		], $withHash ? [
			$this->idKey => $file->hashName()
		] : []);
	}

	protected function file_to_json($file, $column, $withHash = false)
	{
		return json_encode($this->file_to_array($file, $column, $withHash));
	}

	protected function files_to_json($files, $column, $withHash = false)
	{
		return json_encode(collect($files)->map(function($file) use($column, $withHash) {
			return $this->file_to_array($file, $column, $withHash);
		}));
	}

	protected function assert_in_storage($file, $column)
	{
		Storage::assertExists('public/'.$this->modelPath.'/'.$column.'/'.$file->hashName());
	}

	protected function assert_not_in_storage($file, $column)
	{
		Storage::assertMissing('public/'.$this->modelPath.'/'.$column.'/'.$file->hashName());
	}
}