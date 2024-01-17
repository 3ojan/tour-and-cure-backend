<?php

namespace App\Http\Requests\EmployeesUsers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isClinicOwner() and $this->route('employee')) {
            $employee = $this->route('employee');

            return $employee->user->clinic_id === $user->clinic_id;
        } elseif ($user->isClinicUser() and $this->route('employee')) {
            $employee = $this->route('employee');

            return $employee->id === $user->employee->id;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->route('user')) {
            return $this->getUserUpdateSet();
        } else {
            return $this->getEmployeeUpdateSet();
        }
    }

    private function getUserUpdateSet()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                Rule::unique('users')->ignore($this->user->id)
            ],
            'password' => 'sometimes|min:8|string|confirmed',
        ];
    }

    private function getEmployeeUpdateSet()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                Rule::unique('users')->ignore($this->employee->user->id)
            ],
            'password' => 'sometimes|min:8|string|confirmed',

            'description' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'type' => 'sometimes|string|max:255',
            'is_public' => 'sometimes|boolean',

            'profile_picture' => 'sometimes|exists:media_files,id',
        ];
    }
}
