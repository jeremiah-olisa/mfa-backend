<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPaymentHistoryRequest;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment link by plan ID
     *
     * @param int $plan_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentLinkByPlanId(int $plan_id, Request $request)
    {
        $app = $request->header("MFA_ORGANIZATION");

        return $this->handleErrors(function () use ($plan_id, $app) {
            $response = $this->paymentService->getPaymentLinkByPlanId($plan_id, $app);
            return $this->api_response(
                'Payment link generated successfully.',
                $response,
                201
            );
        });
    }

    public function getAllPaymentPlans()
    {
        return $this->handleErrors(function () {
            $payment_plans = $this->paymentService->getAllPaymentPlans();
            return $this->api_response(
                'Payment plans retrieved successfully.',
                ['payment_plans' => $payment_plans],
                200
            );
        });
    }

    /**
     * Verify payment using the transaction reference
     *
     *
     * @return \Illuminate\Http\JsonResponse|RedirectResponse
     * @throws ValidationException
     * @throws \Throwable
     */
    public function verifyPayment($reference, Request $request)
    {
        $result = $this->paymentService->verifyPayment($reference);

        // Ensure the plan expiry date is formatted for readability
        $formattedExpiryDate = Carbon::parse($result['plan_expires_at'])->toFormattedDateString();

        $message = "You paid for the '{$result['payment_plan']}' plan, which will expire on {$formattedExpiryDate}.";

        if ($request->wantsJson())
            return $this->api_response(
                'Payment verified successfully. ' . $message,
                ['data' => $result],
                200
            );

        return redirect()->route('payment.success')->with([
            'message' => $message,
            'status' => 'success',
            'title' => 'Payment successful!',
        ]);
    }

    public function success()
    {
        return Inertia::render('Payment/Success', [
            'message' => session('message'),
            'status' => session('status'),
            'title' => session('title'),
        ]);
    }

    /**
     * Get payment history (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentHistory(GetPaymentHistoryRequest $request)
    {
        return $this->handleErrors(function () use ($request) {
            $queryParams = $request->validated();
            $perPage = $validatedQuery['per_page'] ?? 15;

            $response = $this->paymentService->getPaymentHistory($queryParams, $perPage);
            [$payments, $paginated] = $this->paginated_response($response);
            return $this->api_response(
                'Payment history retrieved successfully.',
                [
                    'payments' => $payments,
                    'paginated' => $paginated,
                ],
                200
            );
        });
    }

    /**
     * Get the authenticated user's payment history (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyPaymentHistory(GetPaymentHistoryRequest $request)
    {
        return $this->handleErrors(function () use ($request) {
            $queryParams = $request->validated();
            $perPage = $validatedQuery['per_page'] ?? 15;
            $response = $this->paymentService->getMyPaymentHistory($queryParams, $perPage);
            [$payments, $paginated] = $this->paginated_response($response);
            return $this->api_response(
                'Payment history retrieved successfully.',
                [
                    'payments' => $payments,
                    'paginated' => $paginated,
                ],
                200
            );
        });
    }

    /**
     * Get payment history for a specific user (paginated)
     *
     * @param int|string $user_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPaymentHistory(int|string $user_id, GetPaymentHistoryRequest $request)
    {
        return $this->handleErrors(function () use ($user_id, $request) {
            $queryParams = $request->validated();
            $perPage = $validatedQuery['per_page'] ?? 15;

            $response = $this->paymentService->getUserPaymentHistory($user_id, $queryParams, $perPage);
            [$payments, $paginated] = $this->paginated_response($response);
            return $this->api_response(
                'User payment history retrieved successfully.',
                [
                    'payments' => $payments,
                    'paginated' => $paginated,
                ],
                200
            );
        });
    }


}

