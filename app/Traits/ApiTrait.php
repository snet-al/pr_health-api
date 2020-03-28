<?php

namespace App\Traits;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;

trait ApiTrait
{
    /**
     * @param $errors
     *
     * @return string
     */
    public function transformValidationMessages($errors)
    {
        $errorMessages = [];

        $errorsList = is_array($errors) ? $errors : $errors->all();
        foreach ($errorsList as $error) {
            $errorMessages[] = $error;
        }

        return $errorMessages;
    }

    public function validationFailed($errors, $status = ApiCodes::VALIDATION_FAILED)
    {
        $errors = $this->transformValidationMessages($errors);
        $message = 'Validation Failed';

        return $this->restApiGeneralErrorResponse($errors, $message, $status, [], HttpStatusCodes::UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array $data
     * @param null  $message
     * @param null  $status
     * @param array $errors
     * @param int   $httpStatusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restApiResponse($data = [], $message = null, $status = null, $errors = [], $httpStatusCode = 200)
    {
        $json = $this->formatResponse($errors, $message ?? ApiCodes::getSuccessMessage(), $status ?? ApiCodes::SUCCESS, $data);

        return response()->json($json, $httpStatusCode);
    }

    /**
     * @param array $errors
     * @param null  $message
     * @param null  $status
     * @param array $data
     * @param int   $httpsStatusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restApiGeneralErrorResponse($errors = [], $message = null, $status = null, $data = [], $httpsStatusCode = HttpStatusCodes::INTERNAL_SERVER_ERROR)
    {
        $json = $this->formatResponse($errors, $message ?? ApiCodes::getGeneralErrorMessage(), $status ?? ApiCodes::GENERAL_ERROR, $data);

        return response()->json($json, $httpsStatusCode);
    }

    /**
     * @param array $errors
     * @param null  $message
     * @param null  $status
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceNotFound($errors = [], $message = null, $status = null, $data = [])
    {
        $message = $message ?? ApiCodes::getResourceNotFoundMessage();
        $status = $status ?? ApiCodes::RESOURCE_NOT_FOUND;

        return $this->restApiGeneralErrorResponse($errors, $message, $status, $data, HttpStatusCodes::UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array $errors
     * @param null  $message
     * @param null  $status
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceNotActive($errors = [], $message = null, $status = null, $data = [])
    {
        $message = $message ?? ApiCodes::getResourceInactiveMessage();
        $status = $status ?? ApiCodes::RESOURCE_INACTIVE;

        return $this->restApiGeneralErrorResponse($errors, $message, $status, $data, HttpStatusCodes::UNPROCESSABLE_ENTITY);
    }

    /**
     * @param $errors
     * @param $message
     * @param $status
     * @param $data
     *
     * @return array
     */
    private function formatResponse($errors, $message, $status, $data)
    {
        return [
            'message' => $message,
            'status' => $status,
            'data' => $data,
            'errors' => $errors,
        ];
    }
}
