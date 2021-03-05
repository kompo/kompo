<?php

namespace Kompo;

use App\Models\User;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Model extends LaravelModel
{
    public function save(array $options = [])
    {
        if (defined(get_class($this).'::CREATED_BY') && !$this->getKey() && static::CREATED_BY && auth()->check()) {
            $this->{static::CREATED_BY} = auth()->user()->id;
        }

        if (defined(get_class($this).'::UPDATED_BY') && static::UPDATED_BY && auth()->check()) {
            $this->{static::UPDATED_BY} = auth()->user()->id;
        }

        parent::save($options);
    }

    /* RELATIONS */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
