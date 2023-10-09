<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'required|min:3|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id)],
            'password' => 'string|min:6|max:64|confirmed|nullable',
            'password_confirmation' => 'string|min:6|max:64|nullable',
        ];

    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('validation.common.required'),
            'name.min' => __('validation.common.min') .' ' . ':min',
            'name.max' => __('validation.common.max') .' ' . ':max',

            'email.required' => __('validation.common.required'),
            'email.email' => __('validation.common.email'),
            'email.unique' => __('validation.common.unique'),
            
            'password.min' => __('validation.common.min') .' ' . ':min',
            'password.max' => __('validation.common.max') .' ' . ':max',
            'password_confirmation.min' => __('validation.common.min') .' ' . ':min',
            'password_confirmation.max' => __('validation.common.max') .' ' . ':max',
        ];
    }

}
