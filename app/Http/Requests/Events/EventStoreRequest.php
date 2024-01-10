<?php

namespace App\Http\Requests\Events;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if (!$user->isPatient()) {
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
//            'clinic_id' => 'required|exists:clinics,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'data' => 'required|json'
        ];
    }
}
