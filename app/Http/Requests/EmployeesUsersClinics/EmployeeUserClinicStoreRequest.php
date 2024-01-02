<?php

namespace App\Http\Requests\EmployeesUsersClinics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeUserClinicStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($user and $user->role === 'admin') {
            return true;
        } elseif ($user and $user->role === 'clinic_owner') {
            return true;
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
        if ($this->has('clinic_id')) {
            return $this->getClinicUserSet();
        } elseif ($this->hasFile('profile_picture') && count($this->all()) === 1) {
            return $this->getFileSet();
        } else {
            return $this->getClinicOwnerSet();
        }
    }

    /**
     * Get user and employee validation set.
     *
     * @return array
     */
    protected function getClinicUserSet(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required|required_with:password|same:password|string',
            'role' => [
               'required',
               Rule::in(['clinic_owner', 'clinic_user'])
            ],
            'clinic_id' => 'required|string|exists:clinics,id',

            'employee_description' => 'sometimes|string|max:255',
            'employee_phone' => 'sometimes|string',
            'employee_type' => 'sometimes|string|max:255',
            'employee_picture' => 'sometimes|exists:media_files,id',
        ];
    }

    /**
     * Get file validation set.
     *
     * @return array
     */
    protected function getFileSet(): array
    {
        return [
            'profile_picture' => 'required|file|max:10240|mimes:jpeg,png,gif,webp'
        ];
    }

    /**
     * Get user, employee and clinic validation set.
     *
     * @return array
     */
    protected function getClinicOwnerSet(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|required_with:password_confirmation|min:8|string|confirmed',
            'password_confirmation' => 'required|required_with:password|same:password|string',

            'employee_description' => 'sometimes|string|max:255',
            'employee_phone' => 'sometimes|string',
            'employee_type' => 'sometimes|string|max:255',
            'employee_picture' => 'sometimes|exists:media_files,id',

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
