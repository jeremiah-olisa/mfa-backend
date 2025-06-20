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
use App\Traits\TracksFileName;
use Maatwebsite\Excel\Validators\Failure;

class QuestionsImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors, TracksFileName;

    public function startRow(): int
    {
        return 2;
    }

    //    public function batchSize(): int
    //    {
    //        return 2;
    //    }

    public function model(array $row)
    {

        // Generate a unique, incremented question_id with suffix
        $baseQuestionId = trim($row[0]);
        $questionId = Str::slug($baseQuestionId . '---' . uuid_create());


        // Create the question
        $question = Question::create([
            'question_id' => $questionId,
            'test_type' => trim(strval($row[1])),
            'section' => trim(strval($row[3])),
            'subject_id' => Subject::where('name', trim(strval($row[2])))->firstOrFail()->id, // Ensure subject exists
            'question' => trim(strval($row[4])),
        ]);

        // Prepare options
        $options = [
            'A' => trim(strval($row[5])),
            'B' => trim(strval($row[6])),
            'C' => trim(strval($row[7])),
            'D' => trim(strval($row[8])),
        ];

        // Identify the correct answer key
        $correctAnswerKey = trim(strval($row[9]));

        // Loop through options and add only non-correct answers
        foreach ($options as $key => $text) {
            $question->options()->create([
                'option' => $text,
                'option_key' => $key . '---' . uuid_create(),
                'question_id' => $question->id,
                'is_correct' => $key == $correctAnswerKey,
            ]);
        }

        return $question; // Return the created question
    }


    /**
     * @param Failure[] $failures
     */
    //    public function onFailure(Failure ...$failures)
//    {
//        // Ensure $errors is initialized
//        $this->errors = $this->errors ?? [];
//
//        foreach ($failures as $failure) {
//            // Add each failure's row and error messages to the errors property
//            $this->errors[] = [
//                'row' => $failure->row(), // Row where the failure occurred
//                'attribute' => $failure->attribute(), // Attribute that caused the error
//                'errors' => $failure->errors(), // Array of error messages
//            ];
//        }
//
//    }

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
            '2.exists' => 'The Subject Name must exist in the list of subjects.',

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

    public function handleErrorsAndFailures(): void
    {
        $errors = [];
        $failures = [];

        // Collect validation errors
        if (method_exists(self::class, 'errors') && self::errors()) {
            foreach (self::errors() as $error) {
                $errors[] = $error; // Adjust based on the error structure
            }
        }

        // Collect validation failures
        if (method_exists(self::class, 'failures') && self::failures()) {
            foreach (self::failures() as $failure) {
                $failures[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
        }

        // Throw an exception if there are any errors or failures
        // Throw an exception if there are any errors or failures
        if (!empty($errors) || !empty($failures)) {
            $flattenedMessages = [];

            // Flatten errors
            foreach ($errors as $error) {
                $flattenedMessages[] = 'Error: ' . $error->getMessage(); // Adjust as per error structure
            }

            // Flatten failures
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

            // Use withMessages and pass the flattened messages
            throw ValidationException::withMessages($flattenedMessages);
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
