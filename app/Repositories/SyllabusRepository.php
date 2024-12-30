<?php

namespace App\Repositories;

use App\Models\ExamSubjectSyllabus;
use App\Models\User;

/**
 * @template T of User
 * @template-inherits BaseRepository<T>
 */
class SyllabusRepository extends BaseRepository
{
    public function __construct(ExamSubjectSyllabus $examSubjectSyllabus)
    {
        parent::__construct($examSubjectSyllabus);
    }

    public function getSyllabusByExam(string $exam): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()->where('exam', $exam)
            ->with('subject:id,name,label,icon_url')
            ->get();
    }
}
