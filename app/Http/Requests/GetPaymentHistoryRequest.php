<?php

namespace App\Http\Requests;

use App\Traits\PaginationRules;
use Illuminate\Foundation\Http\FormRequest;

class GetPaymentHistoryRequest extends FormRequest
{
    use PaginationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->paginationRules([
                'user_id' => 'nullable|exists:users,id|integer', // Ensure the user exists in the users table
                'amount' => 'nullable|numeric|min:99.99', // Positive decimal value
                'payment_method' => 'nullable|string|max:255', // Allow payment_method to be nullable and a string
                'status' => 'nullable|string|in:pending,completed,failed,canceled', // Example statuses
                'reference' => 'nullable|string|unique:payments,reference|max:255', // Ensure reference is unique in payments table
                'paid_at' => 'nullable|date', // Paid_at is optional but must be a valid date if provided
                'payment_plan_id' => 'nullable|exists:payment_plans,id|integer', // Optional, but must exist in the payment_plans table
            ]),
        );
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);


        return $this->validatedFields($data, $this->rules());
    }
}
