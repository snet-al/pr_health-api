<?php

namespace App\Traits;

use Spatie\Permission\Models\Permission;

trait ExposePermissions
{
    /**
     * Get all user permissions.
     *
     * @return bool
     */
    public function getAllPermissionsAttribute()
    {
        if (! \Auth::check()) {
            return null;
        }

        return $this->getAllPermissions();
    }

    /**
     * Get all user permissions in a flat array.
     *
     * @return array
     */
    public function getCanAttribute()
    {
        $permissions = [];
        if (! \Auth::check()) {
            return $permissions;
        }

        foreach (Permission::all() as $permission) {
            if (\Auth::user()->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }

        return $permissions;
    }
}
