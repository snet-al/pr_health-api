<?php

namespace App\Traits;

trait HasPermissions
{
    /**
     * A role can have many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Assign permission for role.
     *
     * @param $permission
     *
     * @return mixed
     */
    public function assignPermission($permission)
    {
        return $this->permissions()->attach($permission);
    }

    /**
     * Assign permissions for role.
     *
     * @param array $permissions
     */
    public function assignPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->assignPermission($permission);
        }
    }

    /**
     * Remove role for user.
     *
     * @param $permission .
     *
     * @return mixed
     */
    public function removePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }

    /**
     * Get list of permissions for role.
     *
     * @return array
     */
    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->permissions as $permission) {
            $permissions[] = $permission->name;
        }

        return $permissions;
    }
}
