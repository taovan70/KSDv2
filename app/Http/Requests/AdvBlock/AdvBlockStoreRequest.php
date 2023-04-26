<?php

namespace App\Http\Requests\AdvBlock;

use Illuminate\Foundation\Http\FormRequest;

class AdvBlockStoreRequest extends FormRequest
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
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'active' => 'required|boolean',
            'device_type' => 'required|string|min:1|max:255',
            'color_type' => 'required|string|min:1|max:255',
            'adv_page_id' => 'required|integer|exists:adv_pages,id',
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
