<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Http\Requests\GetQuestionsRequest;
use App\Imports\QuestionsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\{QuestionRepository, SubjectRepository};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class QuestionsController extends Controller
{
    protected QuestionRepository $questionRepository;
    protected SubjectRepository $subjectRepository;

    public function __construct(QuestionRepository $questionRepository, SubjectRepository $subjectRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function all(Request $request)
    {
        $allQuestions = $this->questionRepository->all(relationship: ['subject:id,label', 'options']);

        $data = ['questions' => $allQuestions];

        return new JsonResponse($data);
    }

    public function all_test_type($test_type)
    {
        $allQuestions = $this->questionRepository->findBy('test_type', $test_type, relationship: ['subject:id,label', 'options']);

        $data = ['questions' => $allQuestions];

        return new JsonResponse($data);
    }

    public function list(GetQuestionsRequest $request)
    {
        $validatedQuery = $request->validated();
        $response = $this->questionRepository->getPaginatedQuestionsWithSubject($validatedQuery, $request->query('per_page') ?? 15);

        $subjects = $this->subjectRepository->all(['id', 'label']);

        $currentQueryParams = collect(request()->query())->except('page')->all();

        $pagination = [
            'next_page_url' => $response->nextPageUrl() ? 
                $response->nextPageUrl() . '&' . http_build_query($currentQueryParams) : 
                null,
            'prev_page_url' => $response->previousPageUrl() ? 
                $response->previousPageUrl() . '&' . http_build_query($currentQueryParams) : 
                null,
            'per_page' => $response->perPage(),
            'current_page' => $response->path(),
            'next_cursor' => $response->nextCursor(),
            'prev_cursor' => $response->previousCursor(),
            'has_more_pages' => $response->hasMorePages(),
            'path' => $response->path(),
            'with_query_string' => $response->withQueryString(),
        ];

        $data = [
            'questions' => $response->items(),
            'pagination' => $pagination,
            'subjects' => $subjects,
            'exams' => SetupConstant::$exams
        ];

        if (!$request->wantsJson())
            return Inertia::render('Questions/List', $data);

        return new JsonResponse($data);
    }


    public function details($question_id, Request $request)
    {
        $question = $this->questionRepository->findOneByOrThrow('question_id', $question_id, ['options', 'subject:id,label']);

        $data = ['question' => $question];
        if (!$request->wantsJson())
            return Inertia::render('Questions/Details', $data);

        return new JsonResponse($data);
    }

    public function destroy($question_id, Request $request)
    {
        $this->questionRepository->deleteQuestionsByOrThrow('question_id', $question_id);

        if (!$request->wantsJson())
            return redirect()->back()->with('success', 'Question deleted successfully.');

        return new JsonResponse([], ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * @throws ValidationException
     */
    public function upload(Request $request)
    {
        $import = new QuestionsImport();
        $excel = Excel::import($import, $request->file('file'));

        $import->handleErrorsAndFailures();


        return redirect()->back()->with('success', 'Questions uploaded successfully . ');
    }
}
