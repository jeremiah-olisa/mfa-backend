<?php

namespace App\Services;

use App\Repositories\SyllabusRepository;

class SyllabusService
{
    protected SyllabusRepository $syllabusRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SyllabusRepository $syllabusRepository)
    {
        $this->syllabusRepository = $syllabusRepository;
    }

    public function getSyllabusByExam(string $exam): \Illuminate\Database\Eloquent\Collection
    {
        return $this->syllabusRepository->getSyllabusByExam($exam);
    }
}
