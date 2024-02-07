<?php

namespace App\Http\Requests\EmployeesUsers;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EmployeeUserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isClinicOwner() || $user->isClinicUser()) {
            return $request->clinic_id === $user->clinic_id;
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
            'name' => 'sometimes|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8|string|confirmed',
            'role' => [
                'required',
                Rule::in(['clinic_owner', 'clinic_user'])
            ],
            'clinic_id' => 'sometimes|string|exists:clinics,id',

            'description' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'type' => 'sometimes|string|max:255',
            'is_public' => 'sometimes|boolean',
            'profile_picture' => 'sometimes|exists:media_files,id',
        ];
    }
}
