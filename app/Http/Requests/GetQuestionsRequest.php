<?php

namespace App\Http\Requests;

use App\Traits\PaginationRules;
use Illuminate\Foundation\Http\FormRequest;

class GetQuestionsRequest extends FormRequest
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
                'question_id' => 'nullable|string|max:255', // Ensure it is a string and not empty
                'test_type' => 'nullable|string|max:255',  // Ensure it is a valid string
                'subject' => 'nullable|string',
                'search' => 'nullable|string|max:1000',  // Ensure it is a string and not empty
                'question' => 'nullable|string|max:1000',  // Ensure it is a string and not empty
            ]),
        );
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);


        return $this->validatedFields($data, $this->rules());
    }
}
