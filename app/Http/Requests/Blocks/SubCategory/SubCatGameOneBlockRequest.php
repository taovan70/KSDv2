<?php

namespace App\Http\Requests\Blocks\SubCategory;


use Illuminate\Foundation\Http\FormRequest;

class SubCatGameOneBlockRequest extends FormRequest
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
            'question' => 'required|string|min:2|max:255',
            'category_id' => 'required|numeric|exists:categories,id',
//            'answer_data' => 'nullable|array',
//            'answer_data.*.answer' => 'required|string',
//            'answer_data.*.is_correct' => 'required|boolean',
            'photo_path' => 'nullable|image|max:4096'
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
            'question.required' => __('validation.common.required'),
            'photo_path.required' => __('validation.common.required'),
            'category_id.required' => __('validation.common.required'),
            'question.max' => __('validation.common.max') . ' ' . ':max',
            'question.min' => __('validation.common.min') . ' ' . ':min',
            'answer_data.*.answer' => __('validation.common.required'),
            'answer_data.*.is_correct' => __('validation.common.required'),
        ];
    }
}
