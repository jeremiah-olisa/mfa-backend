<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user()->id], // Unique constraint, excluding the current user's email
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'], // Optional phone number, should be a valid phone number
            'parent_email' => ['nullable', 'email', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{10,15}$/'], // Optional parent phone number, valid phone format
        ];
    }
}
