<?php

namespace App\Imports;

use App\Constants\SetupConstant;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\{Question, Subject};
use App\Traits\ImportModelTraits;
use App\Traits\TracksFileName;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Validators\Failure;

class QuestionsImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, SkipsOnFailure, SkipsOnError, WithBatchInserts
{
    use SkipsFailures, SkipsErrors, TracksFileName, ImportModelTraits;

    public function model(array $row)
    {
        return $this->createQuestion([
            'question_text' => $row[4],
            'exam_type' => $row[1],
            'question_type' => $row[3],
            'subject_name' => $row[2],
            'question_image' => null, // V1 doesn't have image
            'options' => [
                'A' => $row[5],
                'B' => $row[6],
                'C' => $row[7],
                'D' => $row[8],
                'correct' => $row[9]
            ]
        ]);
    }

    protected function createOptions(Question $question, $optionsData): void
    {
        foreach ($optionsData as $key => $text) {
            if ($key === 'correct')
                continue;

            $question->options()->create([
                'option' => $this->sanitizeOptionText($text),
                'option_key' => $key . '---' . uuid_create(),
                'question_id' => $question->id,
                'is_correct' => $key === $optionsData['correct'],
            ]);
        }
    }


    public function rules(): array
    {
        return [
            '0' => ['required', 'max:255'], // question_id (base ID)
            '1' => ['required', 'in:' . implode(',', SetupConstant::$exams)], // test_type
            '2' => ['required', 'exists:subjects,name'], // subject name
            '3' => ['max:255'], // section
            '4' => ['required', 'max:1000', 'unique:questions,question'], // question text
            '5' => ['required', 'max:500'], // Option A
            '6' => ['required', 'max:500'], // Option B
            '7' => ['required', 'max:500'], // Option C
            '8' => ['required', 'max:500'], // Option D
            '9' => ['required', 'in:A,B,C,D'], // Correct answer key
        ];

    }

    /**
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            '0' => 'Question ID',
            '1' => 'Test Type',
            '2' => 'Subject Name',
            '3' => 'Section',
            '4' => 'Question Text',
            '5' => 'Option A',
            '6' => 'Option B',
            '7' => 'Option C',
            '8' => 'Option D',
            '9' => 'Correct Answer',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'The Question ID is required.',
            '0.string' => 'The Question ID must be a string.',
            '0.max' => 'The Question ID must not exceed 255 characters.',

            '1.required' => 'The Test Type is required.',
            '1.string' => 'The Test Type must be a string.',
            '1.in' => 'The Test Type must be one of the following: ' . implode(', ', SetupConstant::$exams) . '.',

            '2.required' => 'The Subject Name is required.',
            '2.string' => 'The Subject Name must be a string.',
            '2.exists' => 'The Subject must exist in the list of subjects. Valid subjects are: ' . implode(', ', Subject::pluck('name')->toArray()),

            '3.max' => 'The Section must not exceed 255 characters.',

            '4.required' => 'The Question Text is required.',
            '4.string' => 'The Question Text must be a string.',
            '4.max' => 'The Question Text must not exceed 1000 characters.',
            '4.unique' => 'The Question Text has already been used.',

            '5.required' => 'Option A is required.',
            '5.string' => 'Option A must be a string.',
            '5.max' => 'Option A must not exceed 500 characters.',

            '6.required' => 'Option B is required.',
            '6.string' => 'Option B must be a string.',
            '6.max' => 'Option B must not exceed 500 characters.',

            '7.required' => 'Option C is required.',
            '7.string' => 'Option C must be a string.',
            '7.max' => 'Option C must not exceed 500 characters.',

            '8.required' => 'Option D is required.',
            '8.string' => 'Option D must be a string.',
            '8.max' => 'Option D must not exceed 500 characters.',

            '9.required' => 'The Correct Answer Key is required.',
            '9.string' => 'The Correct Answer Key must be a string.',
            '9.in' => 'The Correct Answer Key must be one of the following: A, B, C, or D.',
        ];
    }
}
