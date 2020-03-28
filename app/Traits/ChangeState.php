<?php

namespace App\Traits;

use App\Helpers\StateConstants;

/**
 * Class ChangeState.
 */
trait ChangeState
{
    /**
     * @param string $action
     *
     * @return $this
     */
    public function changeActiveState($action)
    {
        if ($action === StateConstants::ACTIVATE) {
            $this->is_active = true;
        } elseif ($action === StateConstants::DEACTIVATE) {
            $this->is_active = false;
        }

        $this->save();

        return $this;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Get all user permissions.
     *
     * @return bool
     */
    public function getActiveStatusAttribute()
    {
        return getActiveState($this->is_active);
    }
}
