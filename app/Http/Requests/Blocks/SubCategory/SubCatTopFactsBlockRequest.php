<?php

namespace App\Http\Requests\Blocks\SubCategory;

use Illuminate\Foundation\Http\FormRequest;

class SubCatTopFactsBlockRequest extends FormRequest
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
            'number_one' => 'string|max:255',
            'number_two' => 'string|max:255',
            'number_three' => 'string|max:255',
            'text_one' => 'string|max:2000',
            'text_two' => 'string|max:2000',
            'text_three' => 'string|max:2000',
            'category_id' => 'required|numeric|exists:categories,id',
            'article_one_id' => 'numeric|exists:articles,id',
            'article_two_id' => 'numeric|exists:articles,id',
            'background_photo_path' => 'nullable|image|max:4096'
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
            'category_id.required' => __('validation.common.required'),
            'number_one.max' => __('validation.common.max') . ' ' . ':max',
            'number_two.max' => __('validation.common.max') . ' ' . ':max',
            'number_three.max' => __('validation.common.max') . ' ' . ':max',
            'text_one.max' => __('validation.common.max') . ' ' . ':max',
            'text_two.max' => __('validation.common.max') . ' ' . ':max',
            'text_three.max' => __('validation.common.max') . ' ' . ':max',
            'article_one_id.required' => __('validation.common.required'),
            'article_two_id.required' => __('validation.common.required'),
        ];
    }
}