<?php

namespace App\Services;

use App\Repositories\SubjectRepository;

class SubjectService
{
    protected SubjectRepository $subjectRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getSubjectsWithMultipleQuestionsForExam(string $exam)
    {
        return $this->subjectRepository->getSubjectsWithMultipleQuestionsForExam($exam);
    }
}
