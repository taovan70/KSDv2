<?php

namespace App\Http\Requests\Stories;

use Illuminate\Foundation\Http\FormRequest;

class StoryItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'text' => 'required|string',
            'story_id' => 'required|numeric|exists:categories,id',
            'photo_path' => 'nullable|image|max:4096',
            'article_id' => 'required|numeric|exists:articles,id',
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
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'photo_path.max' => __('validation.common.max') . ' ' . ':max',
            'photo_path.image' => __('validation.common.image'),
            'article_id.required' => __('validation.common.required'),
            'story_id.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
        ];
    }
}
