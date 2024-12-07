<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPaymentHistoryRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    public function getPaymentLinkByPlanId(int $plan_id)
    {
        return $this->handleErrors(function () use ($plan_id) {
            $response = $this->paymentService->getPaymentLinkByPlanId($plan_id);
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
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Throwable
     */
    public function verifyPayment($reference)
    {
        $result = $this->paymentService->verifyPayment($reference);
        return $this->api_response(
            'Payment verified successfully.',
            ['data' => $result],
            200
        );
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

