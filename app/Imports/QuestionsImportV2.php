<?php

namespace App\Imports;

use App\Constants\SetupConstant;
use App\Models\{Question, Subject};
use App\Traits\ImportModelTraits;
use App\Traits\TracksFileName;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImportV2 implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, SkipsOnFailure, SkipsOnError, WithBatchInserts
{
    use SkipsFailures, SkipsErrors, TracksFileName, ImportModelTraits;

    public function model(array $row)
    {
        return $this->createQuestion([
            'question_text' => $row[0],
            'exam_type' => $row[3],
            'question_type' => $row[2],
            'subject_name' => $row[4],
            'question_image' => $row[1],
            'options' => json_decode($row[5], true)
        ]);
    }

    protected function createOptions(Question $question, $optionsData): void
    {
        foreach ($optionsData as $option) {
            $question->options()->create([
                'option' => $this->sanitizeOptionText($option['optionText']),
                'option_key' => $option['label'] . '---' . uuid_create(),
                'question_id' => $question->id,
                'is_correct' => $option['isCorrect'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'max:1000', 'unique:questions,question'], // question
            '1' => ['nullable', 'max:255'], // questionImage
            '3' => ['required', 'in:' . implode(',', array_map('strtolower', SetupConstant::$exams))], // exam type
            '4' => ['required', 'exists:subjects,name'], // subject
            '5' => ['required', 'json'], // options
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            '0' => 'Question Text',
            '1' => 'Question Image',
            '3' => 'Exam Type',
            '4' => 'Subject',
            '5' => 'Options',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '0.required' => 'The Question Text is required.',
            '0.max' => 'The Question Text must not exceed 1000 characters.',
            '0.unique' => 'The Question Text has already been used.',

            '1.max' => 'The Question Image must not exceed 255 characters.',

            '3.required' => 'The Exam Type is required.',
            '3.in' => 'The Exam Type must be one of the following: ' . implode(', ', SetupConstant::$exams) . '.',

            '4.required' => 'The Subject is required.',
            '4.exists' => 'The Subject must exist in the list of subjects. Valid subjects are: ' . implode(', ', Subject::pluck('name')->toArray()),

            '5.required' => 'The Options are required.',
            '5.json' => 'The Options must be in valid JSON format.',
        ];
    }
}