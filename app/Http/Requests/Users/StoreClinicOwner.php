<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClinicOwner extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required|required_with:password|same:password|string',

            'clinic_name' => 'required|string|unique:clinics,name',
            'clinic_address' => 'required|string',
            'clinic_postcode' => 'required|string',
            'clinic_city' => 'required|string',
            'clinic_country_id' => 'required|string|exists:countries,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ];
    }
}
