<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('payments/verify/{reference}', [PaymentController::class, 'verifyPayment'])->name('verifyPayment');

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('payments')->group(function () {

        Route::get('plans', [PaymentController::class, 'getAllPaymentPlans']);
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
