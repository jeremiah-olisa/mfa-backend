<?php

namespace App\Http\Controllers;

use App\Constants\SetupConstant;
use App\Http\Requests\GetQuestionsRequest;
use App\Imports\QuestionsImport;
use App\Imports\QuestionsImportV2;
use App\Imports\SmartMultipleQuestionsImport;
use App\Imports\SmartQuestionsImport;
use App\Utils\PaginationUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\{QuestionRepository, SubjectRepository};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $data = [
            'questions' => $response['data'],
            'pagination' => $response['pagination'],
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
     * Original method for V1 format (backward compatible)
     * @throws ValidationException
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $import = new QuestionsImport();
        $this->uploadQuestion($import, $request->file('file'));
        $import->handleErrorsAndFailures();

        return redirect()->back()->with('success', 'Questions uploaded successfully.');
    }

    /**
     * Dedicated method for V2 format
     * @throws ValidationException
     */
    public function uploadV2(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $import = new QuestionsImportV2();
        $this->uploadQuestion($import, $request->file('file'));
        $import->handleErrorsAndFailures();

        return redirect()->back()->with('success', 'Questions (V2 format) uploaded successfully.');

    }

    /**
     * Smart import method that auto-detects format
     * @throws ValidationException
     */
    public function smartUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $filePath = $request->file('file')->getRealPath();
        $smartImport = new SmartQuestionsImport($filePath);
        $import = $smartImport->selectedImporter;

        $this->uploadQuestion($import, $request->file('file'));

        Log::info("After excell IMPORT");
        // Call handleErrorsAndFailures after import
        $import->handleErrorsAndFailures();

        return redirect()->back()->with('success', 'Questions uploaded successfully (auto-detected format).');
    }

    public function multipleSmartUpload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        $multiImporter = new SmartMultipleQuestionsImport($request->file('files'));
        $multiImporter->importFiles();
        $successCount = $multiImporter->getSuccessCount();
        $multiImporter->handleErrorsAndFailures();

        return redirect()->back()
            ->with('success', "All {$successCount} files were processed successfully!");
    }

    private function uploadQuestion($importer, $file)
    {
        // In your controller
        Excel::import($importer, $file);
    }

}
