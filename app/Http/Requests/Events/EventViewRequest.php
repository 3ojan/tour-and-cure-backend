<?php

namespace App\Http\Requests\Events;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventViewRequest extends FormRequest
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
            //
        ];
    }
}
