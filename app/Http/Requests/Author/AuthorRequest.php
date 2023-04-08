<?php

namespace App\Http\Requests\Author;

use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
            'surname' => 'required|string|min:2|max:255',
            'middle_name' => 'nullable|string|min:2|max:255',
            'age' => 'required|integer|min:1|max:99',
            'gender' => 'required|string|in:' . implode(',', Author::GENDERS),
            'biography' => 'required|string',
            'address' => 'required|string',
            'photo_path' => 'required|image|max:2000',
            'personal_site' => 'nullable|string|url',
            'social_networks' => 'nullable|array',
            'social_networks.*.social_network' => 'required_with:social_networks|string|in:' . implode(',', Author::SOCIAL_NETWORKS),
            'social_networks.*.account' => 'required_with:social_networks|string'
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
