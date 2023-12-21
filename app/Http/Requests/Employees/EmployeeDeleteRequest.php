<?php

namespace App\Http\Requests\Employees;

use App\Models\Employee;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeDeleteRequest extends FormRequest
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
            //
        ];
    }
}
