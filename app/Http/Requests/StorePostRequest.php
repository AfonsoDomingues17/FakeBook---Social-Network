<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'media' => 'array|max:5',
            'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'category' => 'nullable|exists:category,id'
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
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'is_public.boolean' => 'The is_public field must be true or false.',
            'media.array' => 'The media must be an array.',
            'media.max' => 'You may not upload more than 5 media files.',
            'media.*.image' => 'Each media file must be an image.',
            'media.*.mimes' => 'Each media file must be a file of type: jpeg, png, jpg, gif, svg.',
            'media.*.max' => 'Each media file may not be greater than 1024 kilobytes.',
            'category.exists' => 'The selected category is invalid.',
        ];
    }
}