<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'direct_chat_id' => 'nullable|integer|exists:direct_chats,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'content' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'direct_chat_id.integer' => 'The direct chat ID must be an integer.',
            'direct_chat_id.exists' => 'The selected direct chat ID is invalid.',
            'group_id.integer' => 'The group ID must be an integer.',
            'group_id.exists' => 'The selected group ID is invalid.',
            'content.string' => 'The content must be a string.',
            'content.max' => 'The content may not be greater than 1000 characters.',
            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }
}