<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "phone" => "required|string|unique:customers,phone|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/",
            "email" => "required|email|unique:customers,email|max:255",
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            "name.required" => "Customer name is required.",
            "phone.required" => "Phone number is required.",
            "phone.unique" => "This phone number is already registered.",
            "phone.regex" => "Please enter a valid phone number.",
            "email.required" => "Email address is required.",
            "email.email" => "Please enter a valid email address.",
            "email.unique" => "This email address is already registered.",
        ];
    }
}

