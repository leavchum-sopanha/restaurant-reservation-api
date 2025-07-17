<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Table;

class StoreReservationRequest extends FormRequest
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
            "customer_id" => "required|integer|exists:customers,id",
            "table_id" => "required|integer|exists:tables,id",
            "date_time" => "required|date|after:now",
            "note" => "nullable|string|max:1000",
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            "customer_id.required" => "Customer is required.",
            "customer_id.exists" => "Selected customer does not exist.",
            "table_id.required" => "Table is required.",
            "table_id.exists" => "Selected table does not exist.",
            "date_time.required" => "Reservation date and time is required.",
            "date_time.after" => "Reservation must be scheduled for a future date and time.",
            "note.max" => "Note cannot exceed 1000 characters.",
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->table_id && $this->date_time) {
                $table = Table::find($this->table_id);
                if ($table && !$table->isAvailable($this->date_time)) {
                    $validator->errors()->add("table_id", "The selected table is already booked for this time.");
                }
            }
        });
    }
}

