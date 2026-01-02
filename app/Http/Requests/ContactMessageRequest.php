<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:20|regex:/^[\+]?[0-9]{10,15}$/',
            'message' => 'required|string|min:10|max:5000',
            'token' => 'required|string|max:100|exists:contact_messages,token',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number must be in E.164 format (e.g., +1234567890)',
            'token.exists' => 'Invalid token. Please try submitting the form again.',
            'token.size' => 'Invalid token format.',
        ];
    }
}
