<?php

namespace App\Http\Requests\Clinics;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClinicUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($user and $user->role === 'admin|clinic_owner') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                Rule::unique('clinics')->ignore($this->clinic->id)
            ],
            'description' => 'sometimes|string',
            'address' => 'sometimes|string',
            'postcode' => 'sometimes|string',
            'city' => 'sometimes|string',
            'country_id' => 'sometimes|string|exists:countries,id',

            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',

            'web' => 'sometimes|url',
            'email' => [
                'sometimes',
                Rule::unique('clinics')->ignore($this->clinic->id)
            ],
            'mobile' => [
                'sometimes',
                'string',
                Rule::unique('clinics')->ignore($this->clinic->id)
            ],
            'phone' => [
                'sometimes',
                'string',
                Rule::unique('clinics')->ignore($this->clinic->id)
            ],

            'contact_person' => 'sometimes|string',
            'contact_email' => 'sometimes|string',
            'contact_phone' => 'sometimes|string',

            'category_ids' => 'sometimes|array',
            'category_ids.*' => 'exists:categories,id',

            'logo_image_id' => 'sometimes'
        ];
    }
}
