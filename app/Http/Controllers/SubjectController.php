<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected SubjectService $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function getSubjectsWithMultipleQuestionsForExam(Request $request, $test_type = null)
    {
        $exam = $test_type ?? $this->getExamTypeFromHeader($request);

        $subjects = $this->subjectService->getSubjectsWithMultipleQuestionsForExam($exam);

        return $this->api_response('Subjects retrieved successfully', ['subjects' => $subjects]);
    }
}
