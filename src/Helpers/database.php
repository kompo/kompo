<?php 

//TODO delete this code because moved to kompo/kompo

if (!function_exists('addMetaData')) {
    function addMetaData($table)
    {
        $table->id();
        addedModifiedByColumns($table);

        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrentOnUpdate();

        $table->softDeletes();
    }
}

if (!function_exists('addedModifiedByColumns')) {
    function addedModifiedByColumns($table)
    {
        $table->foreignId('added_by')->nullable()->constrained('users');
        $table->foreignId('modified_by')->nullable()->constrained('users');
    }
}

if (!function_exists('isWhereCondition')) {
    function isWhereCondition($argument)
    {
        return is_null($argument) || is_string($argument) || is_int($argument);
    }
}

if (!function_exists('scopeWhereBelongsTo')) {
    function scopeWhereBelongsTo($query, $columnName, $itemOrItems, $defaultValue = null)
    {
        if (isWhereCondition($itemOrItems)) {
            $query->where($columnName, $itemOrItems ?: $defaultValue);
        } else {
            $query->whereIn($columnName, $itemOrItems);
        } 
    }
}