<?php

namespace App\Http\Requests\Blocks\SubCategory;

use Illuminate\Foundation\Http\FormRequest;

class SubCatCalendarRequest extends FormRequest
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
//            'month_data' => 'nullable|array',
//            'month_data.*.name' => 'required|string',
//            'month_data.*.text' => 'required|string',
//            'month_data.*.article_id' => 'required|string',
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
            'category_id.required' => __('validation.common.required'),
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'name.min' => __('validation.common.min') . ' ' . ':min',
            'month_data.*.name' => __('validation.common.required'),
            'month_data.*.text' => __('validation.common.required'),
            'month_data.*.article_id' => __('validation.common.required'),
        ];
    }
}
