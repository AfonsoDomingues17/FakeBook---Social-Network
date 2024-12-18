<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'selected_users' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The group name is required.',
            'name.string' => 'The group name must be a string.',
            'name.max' => 'The group name may not be greater than 50 characters.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The group discription may not be greater than 255 characters.',
            'photo_url.image' => 'The photo must be an image.',
            'photo_url.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif.',
            'photo_url.max' => 'The photo may not be greater than 2048 kilobytes.',
            'selected_users.string' => 'The selected users must be a string.',
        ];
    }
}