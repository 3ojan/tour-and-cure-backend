<?php

namespace App\Http\Requests\Categories;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($user and $user->role === 'admin') {
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
            'code' => 'sometimes|string',
            'en' => 'sometimes|string',
            'hr' => 'sometimes|string',
            'parent_id' => 'sometimes|string'
        ];
    }
}
