<?php

namespace Kompo\Komponents\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

trait HasModel
{
    /**
     * Assigns the record key or id to the Form.
     *
     * @param mixed $modelKey (optional) The record's key or id in the DB table.
     *
     * @return mixed
     */
    public function modelKey($modelKey = null)
    {
        return $this->_kompo('modelKey', $modelKey);
    }

    /**
     * Returns or sets the record being updated/inserted by the form or null if the form is not linked to a Record.
     *
     * @return Illuminate\Database\Eloquent\Model|Builder|null
     */
    public function model($model = null) //the model can also be set dynamically in the created phase
    {
        return $model ? $this->setModel($model) : $this->model;
    }

    /**
     * Initialize or find the model (if form linked to a model).
     *
     * @param Illuminate\Database\Eloquent\Model|null $model
     *
     * @return Illuminate\Database\Eloquent\Model|null
     */
    public function setModel($model = null)
    {
        if (is_null($model)) {
            return;
        }

        $model = is_subclass_of($model, KompoModelFacade::class) ? $model::getClass() : $model;

        $this->model = $model instanceof Model ? $model : (
            $this->modelKey() ? $model::findOrNew($this->modelKey()) : new $model
        );
        $this->modelKey($this->model()->getKey()); //set if it wasn't (ex: dynamic model set in created() phase)

        $this->modelExists = $this->model->exists;

        return $this->model;
    }
}
