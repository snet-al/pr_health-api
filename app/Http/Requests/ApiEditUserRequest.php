<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiEditUserRequest extends FormRequest
{
    public static $rules = [
        'first_name' => ['min:3'],
        'last_name' => ['min:3'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($userId = '', $clientId = '')
    {
        return [
            'first_name' => ['min:3'],
            'last_name' => ['min:3'],
            'email' => 'sometimes|nullable|email|unique:users,email,' . $userId,
            'phone' => 'min:13|max:20|unique:clients,phone,' . $clientId,
        ];
    }

    public function messages()
    {
        return [
            'email.email' => __('validation.valid_email'),
            'email.unique' => __('validation.unique_email'),
            'first_name.required' => __('validation.required_name'),
            'last_name.required' => __('validation_required_city_id'),
        ];
    }
}
