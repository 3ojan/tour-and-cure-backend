<?php

namespace App\Http\Requests\Events;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return true;
        } elseif (!$user->isPatient() and $this->route('event')) {
            $event = $this->route('event');
            if ($event->clinic_id === $user->clinic_id) {
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
//            'clinic_id' => 'required|exists:clinics,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'title' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'employee_ids' => 'sometimes|array',
            'employee_ids.*' => 'exists:employees,id',
            'data' => 'sometimes|json'
        ];
    }
}
