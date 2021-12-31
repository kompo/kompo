<?php

namespace Kompo\Elements\Traits;

trait HasRolePermission
{
    public function role($role)
    {
        if (auth()->user() && auth()->user()->hasRole($role)) {
            return $this;
        }

        return; //else return null
    }

    public function can($action, $object)
    {
        if (auth()->user() && auth()->user()->can($action, $object)) {
            return $this;
        }

        return; //else return null
    }
}
