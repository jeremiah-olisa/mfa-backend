<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Services\SyllabusService;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    protected SyllabusService $syllabusService;

    public function __construct(SyllabusService $syllabusService)
    {
        $this->syllabusService = $syllabusService;
    }

    public function getSyllabusByExam(Request $request, $test_type = null)
    {
        $exam = $test_type ?? $this->getExamTypeFromHeader($request);

        $syllabi = $this->syllabusService->getSyllabusByExam($exam);

        return $this->api_response('Syllabi retrieved successfully', ['syllabi' => $syllabi]);
    }
}
