<?php

namespace App\Imports;

use App\Constants\SetupConstant;
use App\Models\{Question, Subject};
use Illuminate\Support\Facades\Log;
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
use Maatwebsite\Excel\Validators\Failure;

class QuestionsImportV2 implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Generate a unique question_id
        $questionId = Str::slug($row[0] . '---' . uuid_create());

        // Create the question
        $question = Question::create([
            'question_id' => $questionId,
            'test_type' => trim(strval($row[3])), // examType
            'section' => '', // No section in new format
            'subject_id' => Subject::where('name', trim(strval($row[4])))->firstOrFail()->id, // subject
            'question' => trim(strval($row[0])), // question
            'question_image' => !empty(trim(strval($row[1]))) ? trim(strval($row[1])) : null, // questionImage
        ]);

        // Process options (JSON format)
        $options = json_decode($row[5], true);
        Log::info($row[0]);

        foreach ($options as $option) {
            $sanitizedText = $this->sanitizeOptionText($option['optionText']);
            $question->options()->create([
                'option' => $sanitizedText,
                'option_key' => $option['label'] . '---' . uuid_create(),
                'question_id' => $question->id,
                'is_correct' => $option['isCorrect'],
            ]);
        }

        Log::info("IMPORT Creation");


        return $question;
    }

    /**
     * Sanitize option text by removing the label prefix (e.g., "A. " or "B. ")
     */
    protected function sanitizeOptionText(string $optionText): string
    {
        // Remove pattern like "A. " or "B. " from the beginning
        return preg_replace('/^[A-Z]\.\s*/', '', trim($optionText));
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'max:1000', 'unique:questions,question'], // question
            '1' => ['nullable', 'max:255'], // questionImage
            '3' => ['required', 'in:' . implode(',', SetupConstant::$exams)], // examType
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
            '4.exists' => 'The Subject must exist in the list of subjects.',

            '5.required' => 'The Options are required.',
            '5.json' => 'The Options must be in valid JSON format.',
        ];
    }

    public function handleErrorsAndFailures(): void
    {
        $errors = [];
        $failures = [];

        // Collect validation errors
        if (method_exists($this, 'errors') && $this->errors()) {
            foreach ($this->errors() as $error) {
                $errors[] = $error;
            }
        }

        // Collect validation failures
        if (method_exists($this, 'failures') && $this->failures()) {
            foreach ($this->failures() as $failure) {
                $failures[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
        }

        if (!empty($errors) || !empty($failures)) {
            $flattenedMessages = [];

            foreach ($errors as $error) {
                $flattenedMessages[] = 'Error: ' . $error->getMessage();
            }

            foreach ($failures as $failure) {
                $row = $failure['row'] ?? 'Unknown row';
                $attribute = $failure['attribute'] ?? 'Unknown attribute';
                $failureMessages = $failure['errors'] ?? [];

                foreach ($failureMessages as $message) {
                    $flattenedMessages[] = "Row $row, $attribute: $message";
                }
            }

            $flattenedMessages = array_filter($flattenedMessages, fn($message) => !strpos($message, 'The Question Text has already been used.'));

            session()->flash('message', 'Please note that some questions have been uploaded. The only ones that did not upload are shown in the error.');

            throw ValidationException::withMessages($flattenedMessages);
        }
    }
}