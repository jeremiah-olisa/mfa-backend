<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::middleware(['api', \App\Http\Middleware\ValidateMfaOrganizationHeader::class])->group(function () {
    Route::prefix('payments')->group(function () {
        Route::get('verify/{reference}', [PaymentController::class, 'verifyPayment'])->name('verifyPaymentAPI');
        Route::get('plans', [PaymentController::class, 'getAllPaymentPlans']);

        Route::middleware(['auth:sanctum'])->group(function () {

            // Route to generate payment link by plan ID
            Route::post('pay/{plan_id}', [PaymentController::class, 'getPaymentLinkByPlanId']);

            // Route to verify payment by reference

            // Route to get payment history (paginated)
            Route::get('history', [PaymentController::class, 'getPaymentHistory']);

            // Route to get authenticated user's payment history (paginated)
            Route::get('user/history', [PaymentController::class, 'getMyPaymentHistory']);

            // Route to get a specific user's payment history (paginated)
            Route::get('user/{user_id}/history', [PaymentController::class, 'getUserPaymentHistory']);
        });
    });
});
