<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }

    public function getLoginType(): string
    {
        $username = $this->input('username');

        // Check if it's an email
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Check if it's a valid username (4-16 characters, alphanumeric and underscore)
        if (preg_match('/^[a-zA-Z0-9_]{4,16}$/', $username)) {
            return 'username';
        }

        return 'invalid';
    }

    public function getLoginField(): string
    {
        return $this->getLoginType() === 'email' ? 'email' : 'username';
    }
}
