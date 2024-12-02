<?php

namespace App\Repositories;

use App\Models\Question;
use function Laravel\Prompts\search;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * @template T of Question
 * @template-inherits BaseRepository<T>
 */
class QuestionRepository extends BaseRepository
{
    public function __construct(Question $question)
    {
        parent::__construct($question);
    }

    public function getPaginatedQuestionsWithSubject(array|string|null $queryParams = null, int $perPage = 15)
    {
        $subject = $queryParams['subject'] ?? null;
        $search = $queryParams['search'] ?? null;

        // Remove parameters that are not required for filtering
        data_forget($queryParams, 'per_page');
        data_forget($queryParams, 'subject');
        data_forget($queryParams, 'search');

        // Start the query
        $query = $this->model->newQuery();

        // Apply general filtering and sorting
        $this->applyFiltersAndSorting($query, $queryParams);

        // Include the subject relationship and specify only the fields you need
        $query->with(['subject:id,label']);

        // Apply subject filtering if provided
        if ($subject) {
            $query->whereHas('subject', function ($query) use ($subject) {
                $query->where('name', 'LIKE', '%' . $subject . '%')
                    ->orWhere('label', 'LIKE', '%' . $subject . '%');
            });
        }

        // Apply subject filtering if provided
        // Filter by Question ID, Question, Test Type or Subject
        if ($search) {
            $query->where(function ($query) use ($search) {
                // Apply search to related subject fields
                $query->whereHas('subject', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('label', 'LIKE', '%' . $search . '%');
                })
                    // Apply search to fields within the current model
                    ->orWhere('question_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('question', 'LIKE', '%' . $search . '%')
                    ->orWhere('test_type', 'LIKE', '%' . $search . '%');
            });
        }

        // Return paginated results
        return $query->cursorPaginate($perPage);
    }

    public function deleteQuestionsByOrThrow(string $col, $value)
    {
        // Wrap in a database transaction to ensure atomicity
        DB::transaction(function () use ($col, $value) {
            // Fetch questions by column and value
            $questions = $this->model->where($col, $value)->get();

            // Throw an exception if no questions are found
            if ($questions->isEmpty()) {
                throw new ModelNotFoundException("No questions found with {$col} = {$value}");
            }

            // Iterate through the questions and delete associated options
            foreach ($questions as $question) {
                // Ensure options are deleted before the question itself
                $question->options()->delete(); // Assuming 'options' is the relationship name
                $question->delete();
            }
        });
    }






}
