<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required|unique:categories,name|string|min:2|max:255',
            'description' => 'required|string',
            'icon_path' => 'required|image',
            'photo_path' => 'required|image',
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
            'icon_path.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
            'description.required' => __('validation.common.required'),
            'name.max' => __('validation.common.max') .' ' . ':max',
            'name.min' => __('validation.common.min') .' ' . ':min',
            'name.unique' => __('validation.common.unique')
        ];
    }
}
