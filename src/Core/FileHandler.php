<?php

namespace Kompo\Core;

use Illuminate\Support\Facades\Storage;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;

class FileHandler
{
    /**
     * Boolean flag to indicate whether to store file attributes in separate columns.
     *
     * @var array
     */
    protected $attributesToColumns = false;

    /**
     * The specified disk for uploaded files.
     * By default (see config file), it is stored in the 'local' disk for _File() and 'public' disk for _Image().
     */
    protected $disk;

    /**
     * The storage visibility.
     *
     * @var string
     */
    public $visibility = 'public';

    /**
     * Ways of saving file names. slug|hash
     *
     * @var string
     */
    protected $filenameConvention;

    //File column names from config
    protected $allKeys;
    protected $idKey;
    protected $pathKey;
    protected $nameKey;
    protected $mime_typeKey;
    protected $sizeKey;


    protected $filePath;


    public function __construct()
    {
        $this->initializeDisk();

        collect($this->allKeys = config('kompo.files_attributes'))->each(function ($column, $key) {
            $this->{$key.'Key'} = $column;
        });

        $this->filenameConvention = config('kompo.file_name_convention');
    }

    protected function initializeDisk()
    {
        $this->disk = config('kompo.default_storage_disk.file');
    }

    /**
     * Saves the uploaded file or image to the specified disk.
     * By default (see config file), it is stored in the 'local' disk for _File() and 'public' disk for _Image().
     *
     * @param string $disk The disk instance key.
     *
     * @return self
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
    }

    /**
     * Sets the flag for a direct upload to a files table with columns from kompo.files_attributes.
     *
     * @return void
     */
    public function activateAttributesToColumns()
    {
        $this->attributesToColumns = true;

        return $this->pathKey; //set the field name when direct file uploads
    }

    /**
     * Store the file and return the file information to be stored in the DB.
     *
     * @param Illuminate\Http\UploadedFile       $file
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $name
     * @param bool                               $withId
     *
     * @return array
     */
    public function fileToDB($file, $model, $name = null, $withId = false)
    {
        $name = ($this->attributesToColumns || !$name) ? $this->pathKey : $name;

        if (Lineage::isBelongsTo($model, $name)) {
            $model = Lineage::getRelatedNewInstance($model, $name);
            $name = $this->pathKey;
        }

        $modelPath = ModelManager::getStoragePath($model, $name);

        $this->storeOnDisk($file, $modelPath);

        return $this->mapToDB($file, $modelPath, $withId);
    }

    /**
     * Stores on disk.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string                       $modelPath
     *
     * @return void
     */
    protected function storeOnDisk($file, $modelPath)
    {
        $filename = $this->getStoredFileName($file, $modelPath);

        $this->setFilePath($modelPath, $filename);

        Storage::disk($this->disk)->putFileAs($modelPath, $file, $filename, $this->visibility);
    }


    protected function setFilePath($modelPath, $filename)
    {
        $this->filePath = $modelPath.'/'.$filename;
    }

    /**
     * Gets the default storage path for Kompo files.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string                       $modelPath
     *
     * @return string
     */
    protected function getStoredFileName($file, $modelPath)
    {
        return ($this->filenameConvention == 'slug') ? static::getUniqueName($file, $modelPath, $this->disk) : static::getHashname($file);
    }

    public static function getHashname($file)
    {
        return $file->hashName();
    }

    protected static function getUniqueName($file, $modelPath, $disk)
    {
        $name = $file->getClientOriginalName();
        $extension_pos = strrpos($name, '.');
        $extension = substr($name, $extension_pos);
        $name = \Str::slug(substr($name, 0, $extension_pos));

        $filename = $name.$extension;
        $i = 0;

        while (\Storage::disk($disk)->exists($modelPath.'/'.$filename)) {
            $i ++;
            $filename = $name.'-'.$i.$extension;
        }

        return $filename;
    }

    /**
     * Map the uploaded file information to the configurable file_attribute keys.
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string                       $modelPath
     * @param bool                         $withId
     *
     * @return array
     */
    protected function mapToDB($file, $modelPath, $withId = false)
    {
        return array_intersect_key( //array_intersect keeps only the columns specified in the config

            array_merge([
                $this->nameKey      => $file->getClientOriginalName(),
                $this->pathKey      => $this->filePath,
                $this->mime_typeKey => $file->getClientMimeType(),
                $this->sizeKey      => $file->getSize(),
            ], $withId ? [
                $this->idKey => $file->hashName(),
            ] : []),
            $this->allKeys
        );
    }

    /**
     * Prepare file information from the DB for Front-End display.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param bool                               $withId
     *
     * @return array
     */
    public function mapFromDB($model, $withId = false)
    {
        return collect($this->allKeys)->map(function ($key) use ($model, $withId) {
            if ($key !== $this->idKey || $withId) {
                return $model->{$key};
            }
        })->filter()->all();
    }

    /**
     * Delete the file if found in Storage.
     *
     * @param mixed $file
     */
    public function unlinkFileIfExists($file)
    {        
        if (!$file) {
            return;
        }

        if ($filePath = $file[$this->pathKey] ?? $file->{$this->pathKey}) {           
            $this->storageDelete($filePath);
        }
    }

    protected function storageDelete($filePath)
    {
        Storage::disk($this->disk)->delete($filePath);
    }

    /**
     * Clean old files if not present in request files.
     *
     * @param array $oldFiles     The old files
     * @param array $requestFiles The new files
     */
    public function unlinkOldFilesInAttribute($oldFiles, $requestFiles)
    {
        if (!$oldFiles) {
            return;
        }

        collect($oldFiles)->map(function ($file) use ($requestFiles) {
            if (!in_array($file[$this->idKey] ?? null, $requestFiles->pluck($this->idKey)->all())) {
                $this->unlinkFileIfExists($file);
            }
        });
    }

    /**
     * Gets the keys without id and path.
     */
    public function getKeysWithoutIdPath()
    {
        return collect($this->allKeys)->filter(function ($key) {
            if (!in_array($key, [$this->idKey, $this->pathKey])) {
                return $key;
            }
        });
    }
}
