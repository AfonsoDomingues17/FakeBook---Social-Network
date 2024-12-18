<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendQuestionResponseRequest extends FormRequest
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
            'response' => 'required|string|max:500',
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
            'response.required' => 'The response field is required.',
            'response.string' => 'The response must be a string.',
            'response.max' => 'The response may not be greater than 500 characters.',
        ];
    }
}