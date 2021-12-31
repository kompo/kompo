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

    protected $disk = 'local';

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        collect(config('kompo.files_attributes'))->each(function ($column, $key) {
            $this->{$key.'Key'} = $column;
        });

        config()->set('filesystems.disks.my-disk', [
            'driver' => 'local',
            'root'   => storage_path('my-disk'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['Intervention\Image\ImageServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Image' => 'Intervention\Image\Facades\Image',
        ];
    }

    protected function createFile($name = null, $sizeKb = 1)
    {
        return UploadedFile::fake()->create($name ?: 'file'.rand(1000, 9999).'.pdf', $sizeKb);
    }

    protected function createImage($name = null, $width = 1, $height = 1)
    {
        return UploadedFile::fake()->image($name ?: 'file'.rand(1000, 9999).'.png', $width, $height);
    }

    protected function file_to_array($file, $column, $withHash = false)
    {
        return array_merge([
            $this->nameKey      => $file->name,
            $this->pathKey      => $this->modelPath.'/'.$column.'/'.$file->hashName(),
            $this->mime_typeKey => $file->getClientMimeType(),
            $this->sizeKey      => $file->getSize(),
        ], $withHash ? [
            $this->idKey => $file->hashName(),
        ] : []);
    }

    protected function file_to_json($file, $column, $withHash = false)
    {
        return json_encode($this->file_to_array($file, $column, $withHash));
    }

    protected function files_to_json($files, $column, $withHash = false)
    {
        return json_encode(collect($files)->map(function ($file) use ($column, $withHash) {
            return $this->file_to_array($file, $column, $withHash);
        }));
    }

    /*** storage ***/

    protected function assert_in_storage($file, $column, $disk = null)
    {
        Storage::disk($disk ?: $this->disk)->assertExists($this->storage_path($file, $column));
    }

    protected function assert_not_in_storage($file, $column, $disk = null)
    {
        Storage::disk($disk ?: $this->disk)->assertMissing($this->storage_path($file, $column));
    }

    protected function get_from_storage($file, $column, $disk = null)
    {
        return Storage::disk($disk ?: $this->disk)->path($this->storage_path($file, $column));
    }

    protected function storage_path($file, $column)
    {
        return $this->modelPath.'/'.$column.'/'.$file->hashName();
    }

    /*** storage thumb ***/

    protected function assert_thumb_in_storage($file, $column, $disk = null)
    {
        Storage::disk($disk ?: $this->disk)->assertExists($this->thumb_storage_path($file, $column));
    }

    protected function assert_thumb_not_in_storage($file, $column, $disk = null)
    {
        Storage::disk($disk ?: $this->disk)->assertMissing($this->thumb_storage_path($file, $column));
    }

    protected function get_thumb_from_storage($file, $column, $disk = null)
    {
        return Storage::disk($disk ?: $this->disk)->path($this->thumb_storage_path($file, $column));
    }

    protected function thumb_storage_path($file, $column)
    {
        return thumb($this->modelPath.'/'.$column.'/'.$file->hashName());
    }
}
