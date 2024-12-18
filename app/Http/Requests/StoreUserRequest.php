<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'age' => 'required|integer|min:13',
            'bio' => 'nullable|string|max:250',
            'is_public' => 'required',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|string|in:Male,Female,Other',
            'country_id' => 'nullable|integer|exists:countries,id',
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
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 250 characters.',
            'username.required' => 'The username field is required.',
            'username.string' => 'The username must be a string.',
            'username.max' => 'The username may not be greater than 250 characters.',
            'username.unique' => 'The username has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 250 characters.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'age.required' => 'The age field is required.',
            'age.integer' => 'The age must be an integer.',
            'age.min' => 'The age must be at least 13.',
            'bio.string' => 'The bio must be a string.',
            'bio.max' => 'The bio may not be greater than 250 characters.',
            'is_public.required' => 'The is_public field is required.',
            'photo_url.image' => 'The photo must be an image.',
            'photo_url.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif.',
            'photo_url.max' => 'The photo may not be greater than 2048 kilobytes.',
            'gender.required' => 'The gender field is required.',
            'gender.string' => 'The gender must be a string.',
            'gender.in' => 'The gender must be one of the following: Male, Female, Other.',
            'country.required' => 'The country field is required.',
            'country_id.integer' => 'The country must be a valid integer.',
            'country_id.exists' => 'The selected country does not exist.',
        ];
    }
}