<?php

namespace App\Http\Requests\Clinics;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClinicStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::user()->isAdmin()) {
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
            'name' => 'required|string|unique:clinics,name',
            'address' => 'sometimes|string',
            'postcode' => 'sometimes|string',
            'city' => 'required|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'country_id' => 'required|string|exists:countries,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id'
        ];
    }
}
