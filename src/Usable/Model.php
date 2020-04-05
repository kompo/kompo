<?php

namespace Kompo;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Schema;

class Model extends LaravelModel
{
    const CREATED_BY = 'created_by';
    const UPDATED_BY = 'updated_by';

    const DELETABLE_BY = null;

    public function save(array $options = [])
    {
    	if(!$this->getKey() && static::CREATED_BY) //  && $this->hasColumn(static::CREATED_BY) ?? no for now
        {
    		$this->{static::CREATED_BY} = optional(auth()->user())->id;
        }

        if(static::UPDATED_BY) //  && $this->hasColumn(static::UPDATED_BY) ?? no for now
        {
    	   $this->{static::UPDATED_BY} = optional(auth()->user())->id;
        }

    	parent::save($options);
    }

    /*protected function hasColumn($column)
    {
        return in_array($column, Schema::connection($this->getConnectionName())->getColumnListing($this->getTable()));
    }*/

    public function deletable()
    {
    	return false;
    }
}