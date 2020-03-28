<?php

namespace App\Traits;

use App\Helpers\StateConstants;

/**
 * Class ChangeStatus.
 */
trait ChangeStatus
{
    /**
     * @param string $statusId
     *
     * @return $this
     */
    public function changeStatus($statusId)
    {
        $this->status_id = $statusId;
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
        return $query->where('status_id', StateConstants::ACTIVE);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status_id == StateConstants::ACTIVE;
    }
}
