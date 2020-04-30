<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Core\RequestData;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;

class MultiFile extends File
{
    /**
     * Flag indicating multiple files are allowed.
     *
     * @var boolean
     */
    public $multiple = true;

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        $value = collect(RequestData::get($requestName))->map(function($file) use($model, $name){

            return $file instanceOf UploadedFile ? 
                
                $this->fileHandler->fileToDB($file, $model, $name, true) : 
                
                $this->convertBackToDb($file);
        });

        $this->fileHandler->unlinkOldFilesInAttribute($oldFiles, $value);

        return $value->count() ? $value : null;
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        if($oldFiles && $oldFiles->count()){

            $keepIds = collect(RequestData::get($requestName))->map(function($file) use($model){ 

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

            $relatedModel = Lineage::findOrFailRelated($model, $name);

            return collect($uploadedFiles)->map(function($file) use($relatedModel){

                return $this->fileHandler->fileToDB($file, $relatedModel);

            });
        }
    }

}
