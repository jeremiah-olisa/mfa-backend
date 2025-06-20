<?php

use App\Repositories\QuestionRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\{ProfileController, QuestionsController};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function (QuestionRepository $questionRepository, UserRepository $userRepository) {
    return Inertia::render('Dashboard', [
        'questions' => $questionRepository->count(),
        'WAEC' => $questionRepository->countBy('test_type', \App\Constants\SetupConstant::$exams[0]),
        'NECO' => $questionRepository->countBy('test_type', \App\Constants\SetupConstant::$exams[1]),
        'JAMB' => $questionRepository->countBy('test_type', \App\Constants\SetupConstant::$exams[2]),
        'users' => $userRepository->count(),
        'students' => $userRepository->countBy('role', \App\Constants\SetupConstant::$roles[0]),
        'admins' => $userRepository->countBy('role', \App\Constants\SetupConstant::$roles[1]),
        'content_managers' => $userRepository->countBy('role', \App\Constants\SetupConstant::$roles[2]),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('payments/verify/{reference}', [PaymentController::class, 'verifyPayment'])->name('verifyPayment');
Route::prefix('payment')->group(function () {
    Route::get('/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
});
Route::middleware('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'list'])->name('users.list');
    });

    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionsController::class, 'list'])->name('questions.list');
        Route::post('/upload', [QuestionsController::class, 'upload'])->name('questions.upload');
        Route::post('/upload/v2', [QuestionsController::class, 'uploadV2'])->name('questions.upload.v2');
        Route::post('/upload/smart', [QuestionsController::class, 'smartUpload'])->name('questions.upload.smart');
        Route::get('/{question_id}', [QuestionsController::class, 'details'])->name('questions.details');
        Route::delete('/{question_id}', [QuestionsController::class, 'destroy'])->name('questions.destroy');
    });

    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionsController::class, 'list'])->name('questions.list');
        Route::post('/upload', [QuestionsController::class, 'upload'])->name('questions.upload');
        Route::get('/{question_id}', [QuestionsController::class, 'details'])->name('questions.details');
        Route::delete('/{question_id}', [QuestionsController::class, 'destroy'])->name('questions.destroy');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
