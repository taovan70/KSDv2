<?php

namespace App\Http\Requests\Article;

use App\Rules\NotContainsString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:articles,id',
            'name' => 'required|string|min:2|max:255',
            'author_id' => 'nullable|integer|exists:authors,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'publish_date' => 'required|date',

            'elements' => 'required|array',
            'elements.*.id' => 'nullable|integer|exists:article_elements,id',
            'elements.*.content' => 'required|string',
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
            //
        ];
    }
}
