<?php

namespace App\Http\Requests\AdvPage;

use Illuminate\Foundation\Http\FormRequest;

class AdvPageRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'slug' => 'required|unique:adv_pages,slug|string|min:2|max:255',
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
            'name.max' => __('validation.common.max') .' ' . ':max',
            'name.min' => __('validation.common.min') .' ' . ':min',
            'slug.required' => __('validation.common.required'),
            'slug.max' => __('validation.common.max') .' ' . ':max',
            'slug.min' => __('validation.common.min') .' ' . ':min',
            'slug.unique' => __('validation.common.unique'),
        ];
    }
}
