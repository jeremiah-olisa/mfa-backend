<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OrganizationTypeController;
use App\Http\Controllers\TechoController;
use App\Http\Middleware\DefaultAcceptJson;
use App\Http\Middleware\FormatExceptionError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['api'])->group(function () {
    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::delete('logout', [AuthenticatedSessionController::class, 'destroy']);
        });
    });

//    Route::middleware('auth:sanctum')->group(function () {
//    });
    Route::get('questions', [\App\Http\Controllers\QuestionsController::class, 'all']);
    Route::get('questions/{test_type}', [\App\Http\Controllers\QuestionsController::class, 'all_test_type']);
});

require __DIR__ . '/payment.php';

