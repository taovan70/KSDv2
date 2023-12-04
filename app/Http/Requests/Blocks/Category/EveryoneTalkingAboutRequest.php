<?php

namespace App\Http\Requests\Blocks\Category;


use Illuminate\Foundation\Http\FormRequest;

class EveryoneTalkingAboutRequest extends FormRequest
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
            'category_id' => 'required|numeric|exists:categories,id',
            'article_one_id' => 'required|numeric|exists:articles,id',
            'article_two_id' => 'required|numeric|exists:articles,id',
            'article_three_id' => 'nullable|numeric|exists:articles,id',
            'article_four_id' => 'nullable|numeric|exists:articles,id',
            'article_five_id' => 'nullable|numeric|exists:articles,id',
            'article_six_id' => 'nullable|numeric|exists:articles,id',
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
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'category_id.required' => __('validation.common.required'),
            'article_one_id.required' => __('validation.common.required'),
            'article_two_id.required' => __('validation.common.required'),
            'article_three_id.required' => __('validation.common.required'),
            'article_four_id.required' => __('validation.common.required'),
            'article_five_id.required' => __('validation.common.required'),
            'article_six_id.required' => __('validation.common.required'),
        ];
    }
}
