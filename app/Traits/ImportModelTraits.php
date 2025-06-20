<?php

namespace App\Traits;

use App\Models\Question;
use Illuminate\Support\Str;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait ImportModelTraits
{
    public function batchSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Create question ID from base text
     */
    protected function generateQuestionId(string $baseText): string
    {
        return Str::slug($baseText . '---' . uuid_create());
    }

    /**
     * Get subject ID by name
     */
    protected function getSubjectId(string $subjectName): int
    {
        return Subject::where('name', trim($subjectName))->firstOrFail()->id;
    }

    /**
     * Create question with common fields
     */
    protected function createQuestion(array $data): Question
    {
        $question = Question::create([
            'question_id' => $this->generateQuestionId($data['subject_name']),
            'test_type' => strtoupper(trim($data['exam_type'])),
            'section' => trim($data['question_type']),
            'subject_id' => $this->getSubjectId($data['subject_name']),
            'question' => trim($data['question_text']),
            'question_image' => !empty(trim($data['question_image'])) ? trim($data['question_image']) : null,
        ]);

        $this->createOptions($question, $data['options']);

        Log::info("Created question: {$question->question_id}");

        return $question;
    }

    /**
     * Create question options
     */
    abstract protected function createOptions(Question $question, $optionsData): void;

    /**
     * Sanitize option text
     */
    protected function sanitizeOptionText(string $text): string
    {
        return trim(preg_replace('/^[A-Z]\.\s*/', '', $text));
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

            Log::info($failureMessages);
            throw ValidationException::withMessages($flattenedMessages);
        }
    }
    // public function uniqueBy()
    // {
    //     return 'email';
    // }
}