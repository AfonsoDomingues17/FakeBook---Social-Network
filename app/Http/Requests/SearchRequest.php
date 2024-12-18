<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query' => 'nullable|string|max:255',
            'type' => 'required|string|in:users,posts,groups',
            'page' => 'nullable|integer|min:1',
            'countries' => 'nullable|string',
            'categories' => 'nullable|string',
            'order' => 'nullable|string',
            'user_country' => 'nullable|integer|exists:countries,id',
            'user_fullname' => 'nullable|string|max:255',
            'user_username' => 'nullable|string|max:255',
            'post_category' => 'nullable|integer|exists:category,id',
            'post_description' => 'nullable|string|max:1000',
            'post_type' => 'nullable|string|in:TEXT,MEDIA',
            'group_name' => 'nullable|string|max:255',
            'group_description' => 'nullable|string|max:1000',
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
            'query.string' => 'The query must be a string.',
            'query.max' => 'The query may not be greater than 255 characters.',
            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'type.in' => 'The type must be one of the following: users, posts, groups.',
            'page.integer' => 'The page must be an integer.',
            'page.min' => 'The page must be at least 1.',
            'countries.string' => 'The countries must be a string.',
            'categories.string' => 'The categories must be a string.',
            'order.string' => 'The order must be a string.',
            'user_country.integer' => 'The user country must be an integer.',
            'user_country.exists' => 'The selected user country is invalid.',
            'user_fullname.string' => 'The user fullname must be a string.',
            'user_fullname.max' => 'The user fullname may not be greater than 255 characters.',
            'user_username.string' => 'The user username must be a string.',
            'user_username.max' => 'The user username may not be greater than 255 characters.',
            'post_category.integer' => 'The post category must be an integer.',
            'post_category.exists' => 'The selected post category is invalid.',
            'post_description.string' => 'The post description must be a string.',
            'post_description.max' => 'The post description may not be greater than 1000 characters.',
            'post_type.string' => 'The post type must be a string.',
            'post_type.in' => 'The post type must be one of the following: TEXT, MEDIA.',
            'group_name.string' => 'The group name must be a string.',
            'group_name.max' => 'The group name may not be greater than 255 characters.',
            'group_description.string' => 'The group description must be a string.',
            'group_description.max' => 'The group description may not be greater than 1000 characters.',
        ];
    }
}