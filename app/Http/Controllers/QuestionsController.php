<?php

namespace App\Http\Controllers;

use App\Imports\QuestionsImport;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class QuestionsController extends Controller
{
    protected QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function list(Request $request): Response
    {
        $response = $this->questionRepository->advancedCursorPaginate($request, $request->query('per_page') ?? 15);

        $pagination = [
            'next_page_url' => $response->nextPageUrl(),
            'prev_page_url' => $response->previousPageUrl(),
            'per_page' => $response->perPage(),
            'current_page' => $response->path(),
            'has_more_pages' => $response->hasMorePages(),
        ];

        return Inertia::render('Questions/List', [
            'questions' => $response->items(),
            'pagination' => $pagination,
        ]);
    }


    public function details(Request $request): Response
    {
        return Inertia::render('Questions/Details');
    }

    /**
     * @throws ValidationException
     */
    public function upload(Request $request)
    {
        $import = new QuestionsImport();
        $excel = Excel::import($import, $request->file('file'));

        $import->handleErrorsAndFailures();


        return back()->with('success', 'Questions uploaded successfully.');
    }
}
