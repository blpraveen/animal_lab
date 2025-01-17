<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecieUpdateRequest extends FormRequest
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
            // 'name' =>  "required|unique:species,name,$this->specie->id|max:255",
            // 'code' =>  "required|unique:species,code,$this->specie->id"
            'name' =>  "required|max:255",
            'code' =>  "required|numeric"
        ];
    }
}
