<?php

namespace App\Http\Requests;

use App\Traits\PaginationRules;
use Illuminate\Foundation\Http\FormRequest;

class GetUsersRequest extends FormRequest
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
                'id' => 'nullable|string|max:255', // Ensure it is a string and not empty
                'app' => 'nullable|string|max:255',  // Ensure it is a valid string
            ]),
        );
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);


        return $this->validatedFields($data, $this->rules());
    }
}
