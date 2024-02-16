<?php

namespace App\Http\Requests\Blocks\MainPage;



use Illuminate\Foundation\Http\FormRequest;

class BigCardArticleRequest extends FormRequest
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
            'article_id' => 'required|numeric|exists:articles,id',
            'photo_path' => 'nullable|image|max:4096',
            'text' => 'string',
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
            'article_id.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
            'photo_path.image' => __('validation.common.image'),
            'name.max' => __('validation.common.max') . ' ' . ':max',
        ];
    }
}
