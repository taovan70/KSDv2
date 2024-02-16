<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('articles')->ignore($this?->article?->id)->whereNull('preview_for'),
            ],
            'slug' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^\S*$/u',
                Rule::unique('articles', 'slug')->ignore($this?->article?->id)->whereNull('preview_for'),
            ],

            'title' => 'required|string|max:500',
            'description' => 'required|string|max:1000',
            'preview_text' => 'nullable|string',
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
     */
    public function attributes(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.common.required'),
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'name.unique' => __('validation.common.unique'),

            'slug.required' => __('validation.common.required'),
            'slug.max' => __('validation.common.max') . ' ' . ':max',
            'slug.unique' => __('validation.common.unique'),
            'slug.regex' => __('validation.common.invalid'),

            'title.required' => __('validation.common.required'),
            'title.max' => __('validation.common.max') . ' ' . ':max',

            'description.required' => __('validation.common.required'),
            'description.max' => __('validation.common.max') . ' ' . ':max',

            'keywords.required' => __('validation.common.required'),
            'keywords.max' => __('validation.common.max') . ' ' . ':max',

            'category_id.required' => __('validation.common.required'),
            'author_id.required' => __('validation.common.required'),
            'tags.min' => __('validation.common.required'),
            'publish_date.required' => __('validation.common.required'),
            'content_markdown.required' => __('validation.common.required'),
            'content_markdown.max' => __('validation.common.max') . ' ' . ':max',
            'mainPic.image' => __('validation.common.image'),
        ];
    }
}
