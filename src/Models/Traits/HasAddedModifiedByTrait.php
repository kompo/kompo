<?php 

namespace Kompo\Models\Traits;

use App\Models\User;

trait HasAddedModifiedByTrait
{
    public function save(array $options = [])
    {
        if (auth()->check()) {
            if (!$this->getKey()) {
                $this->added_by = auth()->id();
            }
            $this->modified_by = auth()->id();
        }

        parent::save($options);
    }

    /* RELATIONSHIPS */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    /* SCOPES */
    public function scopeForAuthUser($query, $userId = null)
    {
        $query->where('added_by', $userId ?: auth()->id());
    }
}