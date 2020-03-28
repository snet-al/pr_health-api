<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ErrorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [],
            'status' => $request->resource_status ?? 0,
            'message' => $request->resource_message ?? 'success',
        ];
    }

    public static function resourceCouldNotBeSaved($message = null)
    {
        request()['resource_message'] = 'Resource could not be saved, please try again. : ' . $message;
        request()['resource_status'] = 1;
    }

    public static function resourceNotFound($message = null)
    {
        request()['resource_message'] = $message ?: 'Resource not found';
        request()['resource_status'] = 3;
    }

    public static function validationFailed($message = null, $status = 2)
    {
        request()['resource_message'] = $message ?: 'Validation failed';
        request()['resource_status'] = $status;
    }
}
