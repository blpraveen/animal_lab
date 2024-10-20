<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StaffUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' =>  "required|max:255",
            'user_name' =>  "required|unique:users,user_name,$this->id,id|max:255",
            'password' => [
                'nullable',
                'string',
                Password::min(6)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ],
            'email' => "required|email|unique:users,email,$this->id,id",
            'role_id' => "required",
            'designation' => "required|max:250",
            'mobile_no' => "required",
            'department' => "required",
            'employee_code' => "required",
            'extension_no' => "nullable",
            'tenure_from' => "required|date_format:Y-m-d",
            'tenure_to' => "nullable|date_format:Y-m-d",
        ];

    }
}
