<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['api', \App\Http\Middleware\ValidateMfaOrganizationHeader::class])->group(function () {
    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::patch('reset-password', [NewPasswordController::class, 'store']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::patch('change-password', [PasswordController::class, 'update']);
            Route::delete('logout', [AuthenticatedSessionController::class, 'destroy']);
            Route::get('user', [UserController::class, 'getCurrentUserProfile']);
            Route::patch('user', [UserController::class, 'update']);
        });
    });

//    Route::middleware('auth:sanctum')->group(function () {
//    });
    Route::get('questions', [\App\Http\Controllers\QuestionsController::class, 'all']);
    Route::get('questions/{test_type}', [\App\Http\Controllers\QuestionsController::class, 'all_test_type']);
    Route::get('subjects/{test_type?}', [\App\Http\Controllers\SubjectSyllabusController::class, 'getSubjectsWithMultipleQuestionsForExam']);
    Route::get('syllabus/{test_type?}', [\App\Http\Controllers\SubjectSyllabusController::class, 'getSyllabusByExam']);
});

require __DIR__ . '/payment.php';

