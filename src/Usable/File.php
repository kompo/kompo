<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Eloquent\ModelManager;
use Kompo\Komponents\Field;
use LogicException;

class File extends Field
{
    public $component = 'File';

    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var boolean
     */
    protected $castsToArray = true;

    /**
     * Boolean flag to indicate whether to store file attributes in separate columns.
     * 
     * @var array
     */
    protected $attributesToColumns = false;

    protected $allKeys;
    protected $idKey;
    protected $pathKey;
    protected $nameKey;
    protected $mime_typeKey;
    protected $sizeKey;

    /**
     * Assign the config columns
     *
     * @param  string  $label  The label
     */
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        collect($this->allKeys = config('kompo.files_attributes'))->each(function($column, $key){
            $this->{$key.'Key'} = $column;
        });
    }

    /**
     * Use this flag if your files table has this default schema: id, name, path, mime_type, size.
     * Note: the name of the field should correspond to the path column.
     *
     * @return     self 
     */
    public function attributesToColumns()
    {
        if(!($this instanceOf File))
            throw new LogicException("Only Kompo\File accepts the attributesToColumns() method.");
        
        $this->attributesToColumns = true;
        $this->name = $this->pathKey; //mandatory
        return $this;
    }

    public function getValueFromModel($model, $name)
    {
        return !$this->attributesToColumns ? ModelManager::getValueFromDb($model, $name) : collect($this->allKeys)->map(function($key) use ($model){
            return $key !== $this->idKey ? $model->{$key} : null;
        })->filter()->all();      
    }

    protected function setAttributeFromRequest($name, $model)
    {
        $oldFile = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if( ($uploadedFile = request()->__get($name)) && $uploadedFile instanceOf UploadedFile){

            $this->unlinkFileIfExists($oldFile);

            $newFile = $this->fileToDB($uploadedFile, $name, $model, !$this->attributesToColumns);

            if(!$this->attributesToColumns)
                return $newFile;

            collect($newFile)->each(function($attribute, $column) use($name){
                if($column !== $name)
                    $this->extraAttributes[$column] = $attribute;
            });

            return $newFile[$name];

        }elseif(!request()->__get($name)){

            $this->unlinkFileIfExists($oldFile);

            if(!$this->attributesToColumns)
                return null;

            if($oldFile->exists)
                collect($this->allKeys)->each(function($key){
                    if(!in_array($key, [$this->idKey, $this->pathKey]))
                        $this->extraAttributes[$key] = null;
                });

            return null;
        }
    }

    protected function setRelationFromRequest($name, $model)
    {
        $oldFile = ModelManager::getValueFromDb($model, $name);
        
        if( ($uploadedFile = request()->__get($name)) && ($uploadedFile instanceOf UploadedFile)){

            $this->unlinkFileIfExists($oldFile);
            $oldFile && $oldFile->delete();
            $value = $this->fileToDB($uploadedFile, $this->pathKey, ModelManager::findOrFailRelated($model, $name));

        }else{
            if(!request()->__get($name) && $oldFile){
                $this->unlinkFileIfExists($oldFile);
                $oldFile->delete();
            }
            $value = null;
        }

        return $value;
    }

    /**
     * Overriden because of attributesToColumns. Checks if the field deals with array value
     *
     * @return     Boolean  
     */
    protected function shouldCastToArray($model, $name)
    {
        return parent::shouldCastToArray($model, $name) && !$this->attributesToColumns;
    }

    protected function fileToDB($file, $name, $model, $withId = false)
    {
        $modelPath = ModelManager::getStoragePath($model, $this->attributesToColumns ? $this->pathKey  : $name);
        $file->store('public/'.$modelPath);

        return array_merge([
            $this->nameKey => $file->getClientOriginalName(),
            $this->pathKey => 'storage/'.$modelPath.'/'.$file->hashName(),
            $this->mime_typeKey => $file->getClientMimeType(),
            $this->sizeKey => $file->getSize()
        ], $withId ? [
            $this->idKey => $file->hashName()
        ] : []);
    }

    protected function unlinkFileIfExists($file)
    {
        if($file){
            $filePath = $file[$this->pathKey] ?? $file->{$this->pathKey};
            if($filePath && file_exists($path = storage_path('app/public'.substr($filePath, 7)) )){
                unlink($path);
                if(($this->withThumbnail ?? false) && file_exists(thumb($path)))
                    unlink(thumb($path));
            }
        }
    }

}
