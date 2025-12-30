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
                'question_id' => 'nullable|string|max:255',
                'test_type' => 'nullable|string|max:255',
                'subject' => 'nullable|string',
                'subject_id' => 'nullable|integer|exists:subjects,id',
                'subject_name' => 'nullable|string|max:255',
                'subject_label' => 'nullable|string|max:255',
                'questions_limit' => 'nullable|integer|min:1|max:200',
                'search' => 'nullable|string|max:1000',
                'question' => 'nullable|string|max:1000',
                'sort' => 'nullable|string|in:randomize,asc,desc',
            ]),
        );
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated($key, $default);


        return $this->validatedFields($data, $this->rules());
    }
}
