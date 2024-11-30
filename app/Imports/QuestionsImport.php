<?php

namespace App\Imports;

use App\Models\{Option, Question, Subject};
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\ToModel;

// class QuestionsImport implements ToModel
class QuestionsImport
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Generate a unique, incremented question_id with suffix
            $baseQuestionId = $row[0];
            $suffix = Question::where('question_id', 'like', "$baseQuestionId%")->count() + 1;
            $questionId = $baseQuestionId . '-' . $suffix;

            // Create the question
            $question = Question::create([
                'question_id' => $questionId,
                'test_type' => $row[1],
                'subject_id' => Subject::where('name', $row[2])->firstOrFail()->id, // Ensure subject exists
                'question' => $row[4],
                'answer_id' => null, // Placeholder
            ]);

            // Prepare options
            $options = [
                'A' => $row[5],
                'B' => $row[6],
                'C' => $row[7],
                'D' => $row[8],
            ];

            // Add options and link correct answer
            foreach ($options as $key => $text) {
                $option = Option::create([
                    'option' => $text,
                    'option_key' => $key,
                    'question_id' => $question->id,
                ]);

                // Set the correct answer
                if ($key === $row[9]) {
                    $question->update(['answer_id' => $option->id]);
                }
            }

            return $question; // Return the created question
        });
    }
}
