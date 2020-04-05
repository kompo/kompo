<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Database\EloquentField;
use Kompo\Database\ModelManager;

class MultiFile extends File
{
    /**
     * Flag indicating multiple files are allowed.
     *
     * @var boolean
     */
    public $multiple = true;

    protected function setAttributeFromRequest($requestName, $name, $model)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        $value = collect(request()->__get($requestName))->map(function($file) use($model, $name){

            return $file instanceOf UploadedFile ? 
                
                $this->fileHandler->fileToDB($file, $model, $name, true) : 
                
                json_decode($file, true);
        });

        $this->fileHandler->unlinkOldFilesInAttribute($oldFiles, $value);

        return $value->count() ? $value : null;
    }

    protected function setRelationFromRequest($requestName, $name, $model)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        if($oldFiles && $oldFiles->count()){

            $keepIds = collect(request()->__get($requestName))->map(function($file) use($model){ 

                return json_decode($file)->{$model->getKeyName()} ?? null; 

            })->all();

            $oldFiles->filter(function($file) use($keepIds, $model) { 

                return !in_array($file->{$model->getKeyName()} ?? '', $keepIds); 

            })->each(function($file) {

                $this->fileHandler->unlinkFileIfExists($file);

                $file->delete(); //No detach, onDelete('cascade') should give the choice.

            });
        }
        
        //Has Many these files will be attached
        if($uploadedFiles = request()->file($requestName)){

            $relatedModel = EloquentField::findOrFailRelated($model, $name);

            return collect($uploadedFiles)->map(function($file) use($relatedModel){

                return $this->fileHandler->fileToDB($file, $relatedModel);

            });
        }
    }

}
