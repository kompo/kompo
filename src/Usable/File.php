<?php

namespace Kompo;

use Illuminate\Http\UploadedFile;
use Kompo\Core\FileHandler;
use Kompo\Core\RequestData;
use Kompo\Database\Lineage;
use Kompo\Database\ModelManager;
use Kompo\Elements\Field;
use Kompo\Elements\Managers\FormField;
use LogicException;

class File extends Field
{
    public $vueComponent = 'File';

    /**
     * Adds a cast to array to the attribute if no cast is present.
     *
     * @var bool
     */
    protected $castsToArray = true;

    /**
     * Boolean flag to indicate whether to store file attributes in separate columns.
     *
     * @var array
     */
    protected $attributesToColumns = false;

    /**
     * The file's handler class.
     */
    protected $fileHandler;

    /**
     * The specified disk for uploaded files.
     * By default (see config file), it is stored in the 'local' disk for _File() and 'public' disk for _Image().
     */
    protected $disk;

    /**
     * Assign the config columns.
     *
     * @param string $label The label
     */
    protected function initialize($label)
    {
        parent::initialize($label);

        $this->setupFileHandler();
    }

    protected function setupFileHandler()
    {
        $this->disk = config('kompo.default_storage_disk.file');

        $this->fileHandler = new FileHandler();
    }

    /**
     * Saves the uploaded file or image to the specified disk.
     * By default (see config file), it is stored in the 'local' disk for _File() and 'public' disk for _Image().
     *
     * @param string $disk The disk instance key.
     *
     * @return self
     */
    public function disk($disk)
    {
        $this->disk = $disk;
        $this->fileHandler->setDisk($disk);

        return $this;
    }

    /**
     * Sets the storage visibility setting.
     * By default, it is 'public'.
     *
     * @param string $visibility The visibility setting (public|private).
     *
     * @return self
     */
    public function visibility($visibility)
    {
        $this->fileHandler->visibility = $visibility;

        return $this;
    }

    /**
     * Use this flag if your files table has this default schema: id, name, path, mime_type, size.
     * Note: the name of the field should correspond to the path column.
     *
     * @return self
     */
    public function attributesToColumns()
    {
        if (!($this instanceof File)) {
            throw new LogicException("Only Kompo\File and Kompo\Image accept the attributesToColumns() method.");
        }

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

    protected function convertBackToDb($requestValue)
    {
        return json_decode($requestValue, true);
    }

    public function setAttributeFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFile = $this->attributesToColumns ? $model : ModelManager::getValueFromDb($model, $name);

        if (Lineage::isBelongsTo($model, $name)) {
            $oldFile = $model->{$name}()->first(); 
        }

        if (($uploadedFile = RequestData::get($requestName)) && $uploadedFile instanceof UploadedFile) {
            $this->fileHandler->unlinkFileIfExists($oldFile);

            $newFile = $this->fileHandler->fileToDB($uploadedFile, $model, $name, !$this->attributesToColumns && !Lineage::isBelongsTo($model, $name));

            if($belongsToId = ModelManager::saveBelongsToRelation($model, $name, $newFile, FormField::getConfig($this, 'extraAttributes'))) {
                $oldFile && $oldFile->delete();
                return $belongsToId;
            }

            if (!$this->attributesToColumns) {
                return $newFile;
            }

            collect($newFile)->each(function ($attribute, $column) use ($name) {
                if ($column !== $name) {
                    FormField::setExtraAttributes($this, [$column => $attribute]);
                }
            });

            return $newFile[$name];
        } elseif (!RequestData::get($requestName)) {
            $this->fileHandler->unlinkFileIfExists($oldFile);

            if (Lineage::isBelongsTo($model, $name)) {
                $oldFile && $oldFile->delete();
            }

            if (!$this->attributesToColumns) {
                return null;
            }

            if ($oldFile->exists) {
                $this->fileHandler->getKeysWithoutIdPath()->each(function ($key) {
                    FormField::setExtraAttributes($this, [$key => null]);
                });
            }

            return null;
        } else {

            if (Lineage::isBelongsTo($model, $name)) {
                return $model->{$name}()->first()->getKey();
            }

            return $this->convertBackToDb(RequestData::get($requestName));
        }
    }

    public function setRelationFromRequest($requestName, $name, $model, $key = null)
    {
        $oldFile = ModelManager::getValueFromDb($model, $name);

        if (($uploadedFile = RequestData::get($requestName)) && ($uploadedFile instanceof UploadedFile)) {
            $this->fileHandler->unlinkFileIfExists($oldFile);

            $oldFile && $oldFile->delete();

            $relatedModel = Lineage::findOrFailRelated($model, $name);

            $value = $this->fileHandler->fileToDB($uploadedFile, $relatedModel);
        } else {
            if (!RequestData::get($requestName) && $oldFile) {
                $this->fileHandler->unlinkFileIfExists($oldFile);

                $oldFile->delete();
            }
            $value = null;
        }

        return $value;
    }
}
