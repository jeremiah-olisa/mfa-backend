<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Models\User;

/**
 * @template T of User
 * @template-inherits BaseRepository<T>
 */
class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $subject)
    {
        parent::__construct($subject);
    }

    public function getSubjectsWithMultipleQuestionsForExam(string $exam): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()->whereHas('questions', function ($query) use ($exam) {
            $query->where('test_type', $exam)
                ->groupBy('subject_id')
                ->havingRaw('COUNT(*) > 1');
        })->withCount('questions')->get();
    }
}
