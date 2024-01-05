<?php

namespace App\Http\Requests\Inquiries;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InquiryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isPatient()) {
            $inquiry = $this->route('inquiry');
            if ($inquiry->user_id === $user->id) {
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
            'category_id' => 'sometimes|array',
            'form_json' => 'sometimes'
        ];
    }
}
