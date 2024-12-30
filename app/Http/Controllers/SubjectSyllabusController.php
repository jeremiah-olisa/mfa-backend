<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Services\SubjectService;
use App\Services\SyllabusService;
use Illuminate\Http\Request;

class SubjectSyllabusController extends Controller
{
    protected SyllabusService $syllabusService;
    protected SubjectService $subjectService;

    public function __construct(SyllabusService $syllabusService, SubjectService $subjectService)
    {
        $this->syllabusService = $syllabusService;
        $this->subjectService = $subjectService;
    }

    public function getSubjectsWithMultipleQuestionsForExam(Request $request, $test_type = null)
    {
        $exam = $test_type ?? $this->getExamTypeFromHeader($request);

        $subjects = $this->subjectService->getSubjectsWithMultipleQuestionsForExam($exam);

        return $this->api_response('Subjects retrieved successfully', ['subjects' => $subjects]);
    }

    public function getSyllabusByExam(Request $request, $test_type = null)
    {
        $exam = $test_type ?? $this->getExamTypeFromHeader($request);

        $syllabi = $this->syllabusService->getSyllabusByExam($exam);

        return $this->api_response('Syllabi retrieved successfully', ['syllabi' => $syllabi]);
    }
}
