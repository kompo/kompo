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
     * @var bool
     */
    public $multiple = true;

    /**
     * Store the order of MultiImages relationship column name.
     *
     * @var string
     */
    protected $orderColumn = null;

    //TODO DOCUMENT
    public function orderable($orderColumn = 'order')
    {
        $this->orderColumn = $orderColumn;

        return $this;
    }

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        $value = collect(RequestData::get($requestName))->map(function ($file) use ($model, $name) {
            if($file instanceof UploadedFile) {
                return $this->fileHandler->fileToDB($file, $model, $name, true);
            }

            return $this->convertBackToDb($file);
        });

        $this->fileHandler->unlinkOldFilesInAttribute($oldFiles, $value);

        return $value->count() ? $value->filter() : null;
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFiles = ModelManager::getValueFromDb($model, $name);

        if ($oldFiles && $oldFiles->count()) {
            $keepIds = collect(RequestData::get($requestName))->map(function ($file) use ($model) {
                return json_decode($file)->{$model->getKeyName()} ?? null;
            })->all();

            if ($this->orderColumn) {
                collect($keepIds)->each(function($id, $order) use ($model, $name) {
                    if ($id) {
                        $related = Lineage::findOrFailRelation($model, $name)->getRelated()->find($id);
                        $related->{$this->orderColumn} = $order;
                        $related->save();
                    }
                });
            }

            $oldFiles->filter(function ($file) use ($keepIds, $model) {
                return !in_array($file->{$model->getKeyName()} ?? '', $keepIds);
            })->each(function ($file) {
                $this->fileHandler->unlinkFileIfExists($file);

                $file->delete(); //No detach, onDelete('cascade') should give the choice.
            });
        }

        //Has Many these files will be attached
        if ($uploadedFiles = RequestData::file($requestName)) {
            $relatedModel = Lineage::findOrFailRelated($model, $name);

            return collect($uploadedFiles)->map(function ($file, $order) use ($relatedModel) {
                $fileSpecsArray = $this->fileHandler->fileToDB($file, $relatedModel);
                
                if ($this->orderColumn) {
                    $fileSpecsArray[$this->orderColumn] = $order;
                }
                
                return $fileSpecsArray;
            });
        }
    }
}
