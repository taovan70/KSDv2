<?php

namespace App\Http\Requests\Blocks\SubCategory;

use Illuminate\Foundation\Http\FormRequest;

class SubCatKnowMoreAboutEachBlockRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'block_data' => 'nullable|array',
            'block_data.*.name' => 'required|string',
            'block_data.*.photo_path' => 'required|string',
            'block_data.*.article_one_id' => 'required|string',
            'block_data.*.article_two_id' => 'required|string',
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
            'block_data.*.name' => __('validation.common.required'),
            'block_data.*.photo_path' => __('validation.common.required'),
            'block_data.*.article_one_id' => __('validation.common.required'),
            'block_data.*.article_two_id' => __('validation.common.required'),
        ];
    }
}
