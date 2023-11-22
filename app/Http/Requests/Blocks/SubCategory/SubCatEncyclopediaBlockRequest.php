<?php

namespace App\Http\Requests\Blocks\SubCategory;

use Illuminate\Foundation\Http\FormRequest;

class SubCatEncyclopediaBlockRequest extends FormRequest
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
            'category_id' => 'required|numeric|exists:categories,id',
            'photo_path' => 'nullable|image|max:4096',
            'article_one_id' => 'required|exists:articles,id',
            'article_two_id' => 'nullable|exists:articles,id',
            'article_three_id' => 'nullable|exists:articles,id',
            'article_four_id' => 'nullable|exists:articles,id',
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
            'text.required' => __('validation.common.required'),
            'category_id.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
            'article_one_id.required' => __('validation.common.required'),
            'article_two_id.required' => __('validation.common.required'),
            'article_three_id.required' => __('validation.common.required'),
            'article_four_id.required' => __('validation.common.required'),
        ];
    }
}
