<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Core\FileHandler;
use Kompo\Core\RequestData;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;
use Kompo\Komponents\Field;
use Kompo\Komponents\FormField;
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

    /**
     * The file's handler
     */
    protected $fileHandler;

    /**
     * Assign the config columns
     *
     * @param  string  $label  The label
     */
    protected function vlInitialize($label)
    {
        parent::vlInitialize($label);

        $this->fileHandler = new FileHandler();
    }

    /**
     * Saves the uploaded file or image to the specified disk. 
     * By default, it is stored in the 'local' disk.
     *
     * @param string $disk   The disk instance key.
     *
     * @return self
     */
    public function disk($disk)
    {
        return $this->fileHandler->setDisk($disk);
    }

    /**
     * Use this flag if your files table has this default schema: id, name, path, mime_type, size.
     * Note: the name of the field should correspond to the path column.
     *
     * @return self 
     */
    public function attributesToColumns()
    {
        if(!($this instanceOf File))
            throw new LogicException("Only Kompo\File accepts the attributesToColumns() method.");
        
        $this->attributesToColumns = true;
        
        $this->name = $this->fileHandler->activateAttributesToColumns(); //mandatory

        return $this;
    }


    public function getValueFromModel($model, $name)
    {
        return !$this->attributesToColumns ? 

            ModelManager::getValueFromDb($model, $name) : 

            $this->fileHandler->mapFromDB($model);
    }

    public function setAttributeFromRequest($requestName, $name, $model)
    {
        $oldFile = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if( ($uploadedFile = RequestData::get($requestName)) && $uploadedFile instanceOf UploadedFile){

            $this->fileHandler->unlinkFileIfExists($oldFile);

            $newFile = $this->fileHandler->fileToDB($uploadedFile, $model, $name, !$this->attributesToColumns);

            if(!$this->attributesToColumns)
                return $newFile;

            collect($newFile)->each(function($attribute, $column) use($name){
                if($column !== $name)
                    FormField::setExtraAttributes($this, [$column => $attribute]);
            });

            return $newFile[$name];

        }elseif(!RequestData::get($requestName)){

            $this->fileHandler->unlinkFileIfExists($oldFile);

            if(!$this->attributesToColumns)
                return null;

            if($oldFile->exists)
                $this->fileHandler->getKeysWithoutIdPath()->each(function($key){
                    FormField::setExtraAttributes($this, [$key => null]);
                });

            return null;
        }
    }

    public function setRelationFromRequest($requestName, $name, $model)
    {
        $oldFile = ModelManager::getValueFromDb($model, $name);
        
        if( ($uploadedFile = RequestData::get($requestName)) && ($uploadedFile instanceOf UploadedFile)){

            $this->fileHandler->unlinkFileIfExists($oldFile);

            $oldFile && $oldFile->delete();

            $relatedModel = Lineage::findOrFailRelated($model, $name);

            $value = $this->fileHandler->fileToDB($uploadedFile, $relatedModel);

        }else{
            if(!RequestData::get($requestName) && $oldFile){

                $this->fileHandler->unlinkFileIfExists($oldFile);

                $oldFile->delete();

            }
            $value = null;
        }

        return $value;
    }

}
