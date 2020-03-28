<?php

namespace App\Traits;

/**
 * Class HasUUID.
 */
trait HasUUID
{
    public static function generateInitializingUUIDS()
    {
        foreach (self::all() as $result) {
            $result->uuid = setRequestUuid($result->uuid);
            $result->save();
        }
    }

    public static function byUuid($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }
}
