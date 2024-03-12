<?php

namespace App\Http\Requests\Blocks;


use Illuminate\Foundation\Http\FormRequest;

class InfoBlockRequest extends FormRequest
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
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:info_blocks,slug,'. $this->id,
            'main_text' => 'nullable|string',
            'add_text' => 'nullable|string',
            'photo_path' => 'nullable|image|max:4096',
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
            'slug.required' => __('validation.common.required'),
            'slug.unique' => __('validation.common.unique'),
            'name.max' => __('validation.common.max') . ' ' . ':max',
            'photo_path.max' => __('validation.common.max') . ' ' . ':max',
            'photo_path.image' => __('validation.common.image'),
        ];
    }
}
