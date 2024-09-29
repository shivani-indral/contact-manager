<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required|numeric|digits_between:10,15',
            ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is mandatory.',
            'lastname.required' => 'The lastname field is mandatory.',
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must be a valid number.',
        ];
    }
}
