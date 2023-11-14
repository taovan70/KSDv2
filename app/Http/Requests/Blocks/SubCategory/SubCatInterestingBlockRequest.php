<?php

namespace App\Http\Requests\Blocks\SubCategory;


use Illuminate\Foundation\Http\FormRequest;

class SubCatInterestingBlockRequest extends FormRequest
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
            'name' => 'required|min:2|max:255',
            'text' => 'required|string',
            'photo_path' => 'nullable|image|max:4096',
            'category_id' => 'required|numeric|exists:categories,id',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
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
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'text.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
            'category_id.required' => __('validation.common.required'),
        ];
    }
}
