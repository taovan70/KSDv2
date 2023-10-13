<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:500',
            'slug' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:1000',
            'keywords' => 'required|string|max:1000',
            'category_id' => 'required|numeric|exists:categories,id',
            'author_id' => 'required|numeric|exists:authors,id',
            'tags' => 'array|min:1',
            'tags.*' => 'numeric|exists:tags,id',
            'publish_date' => 'required|date',
            'published' => 'required|boolean',
            'content_markdown' => 'required|string|max:1000000',
            'mainPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes(): array
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
    public function messages(): array
    {
        return [
            'name.required' => __('validation.common.required'),
            'name.max' => __('validation.common.max') .' ' . ':max',

            'slug.required' => __('validation.common.required'),
            'slug.max' => __('validation.common.max') .' ' . ':max',

            'title.required' => __('validation.common.required'),
            'title.max' => __('validation.common.max') .' ' . ':max',

            'description.required' => __('validation.common.required'),
            'description.max' => __('validation.common.max') .' ' . ':max',
            
            'keywords.required' => __('validation.common.required'),
            'keywords.max' => __('validation.common.max') .' ' . ':max',
           
            'category_id.required' => __('validation.common.required'),
            'author_id.required' => __('validation.common.required'),
            'tags.min' => __('validation.common.required'),
            'publish_date.required' => __('validation.common.required'),
            'content_markdown.required' => __('validation.common.required'),
            'content_markdown.max' => __('validation.common.max') .' ' . ':max',
        ];
    }
}
