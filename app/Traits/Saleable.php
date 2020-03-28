<?php

namespace App\Traits;

use App\Helpers\StateConstants;

/**
 * Class ChangeState.
 */
trait Saleable
{
    /**
     * @param string $action
     *
     * @return $this
     */
    public function changeSaleableState($action)
    {
        if ($action === StateConstants::ACTIVATE) {
            $this->is_saleable = true;
        } elseif ($action === StateConstants::DEACTIVATE) {
            $this->is_saleable = false;
        }

        $this->save();

        return $this;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeSaleable($query)
    {
        return $query->active()->where('is_saleable', true)->where('display_in_ecommerce', true);
    }

    /**
     * @return bool
     */
    public function isSaleable()
    {
        return $this->is_saleable;
    }
}
