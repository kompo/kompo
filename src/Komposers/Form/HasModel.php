<?php

namespace Kompo\Komposers\Form;

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
        return $model ? FormBooter::setModel($this, $model) : $this->model;
    }
}
