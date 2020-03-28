<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
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
    public static function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'birthday' => 'date',
            'phone' => 'required|unique:clients|min:13|max:20',
            'mobile_phone' => 'required|unique:clients|min:13|max:20',
            'provider' => 'required',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return self::getMessages();
    }

    public static function getMessages()
    {
        return [
            'first_name.required' => __('validation.first_name.required'),
            'last_name.required' => __('validation.last_name.required'),
            'email.required' => __('validation.email.required'),
            'email.unique' => __('validation.email.unique'),
            'email.email' => __('validation.email.valid'),
            'email.exists' => __('validation.email.exists'),
            'access_token.required' => __('validation.access_token.required'),
            'provider.required' => __('validation.provider.required'),
            'password.required' => __('validation.password.required'),
        ];
    }
}
