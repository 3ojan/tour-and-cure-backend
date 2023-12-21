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

        if ($user->role === 'admin') {
            return true;
        } elseif ($user->role === 'clinic_owner' and $this->route('employee')) {
            $employee = $this->route('employee');
            if ($employee->user->clinic_id === $user->clinic_id) {
                return true;
            }
            return false;
        } elseif ($user->role === 'clinic_user' and $this->route('employee')) {
            $employee = $this->route('employee');
            if ($employee->id === $user->employee->id) {
                return true;
            }
            return false;
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
            'password' => 'sometimes|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required_with:password|same:password|string',
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
            'password' => 'sometimes|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required_with:password|same:password|string',

            'employee_description' => 'sometimes|string|max:255',
            'employee_phone' => 'sometimes|string',
            'employee_type' => 'sometimes|string|max:255',
        ];
    }
}
