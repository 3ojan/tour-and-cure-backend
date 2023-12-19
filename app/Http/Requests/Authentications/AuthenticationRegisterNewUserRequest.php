<?php

namespace App\Http\Requests\Authentications;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRegisterNewUserRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required|required_with:password|same:password|string',
        ];
    }
}
