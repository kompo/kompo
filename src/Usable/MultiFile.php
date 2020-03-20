<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Eloquent\ModelManager;

class MultiFile extends File
{
    public $multiple = true;

    protected function setAttributeFromRequest($name, $model)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        $value = collect(request()->__get($name))->map(function($file) use($model, $name){
            return $file instanceOf UploadedFile ? $this->fileToDB($file, $name, $model, true) : json_decode($file, true);
        });

        if($oldFiles)
            collect($oldFiles)->map(function($file) use($value){
                if(!in_array($file[$this->idKey] ?? null, $value->pluck($this->idKey)->all() ))
                    $this->unlinkFileIfExists($file);
            });

        return $value->count() ? $value : null;
    }

    protected function setRelationFromRequest($name, $model)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        if($oldFiles && $oldFiles->count()){

            $keepIds = collect(request()->input($name))->map(function($file, $model){ 

                return json_decode($file)->{$model->getKeyName()} ?? null; 

            })->all();

            $oldFiles->filter(function($file) use($keepIds, $model) { 

                return !in_array($file->{$model->getKeyName()} ?? '', $keepIds); 

            })->each(function($file) {

                $this->unlinkFileIfExists($file);
                $file->delete(); //No detach, onDelete('cascade') should give the choice.

            });
        }
        
        //Has Many these files will be attached
        if($uploadedFiles = request()->file($name))

            return collect($uploadedFiles)->map(function($file) use($name, $model){

                return $this->fileToDB($file, $this->pathKey, ModelManager::findOrFailRelated($model, $name));

            });
    }

}
