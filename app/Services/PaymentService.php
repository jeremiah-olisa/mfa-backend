<?php

namespace App\Services;

use App\Repositories\PaymentPlanRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Unicodeveloper\Paystack\Exceptions\PaymentVerificationFailedException;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Validation\ValidationException;

class PaymentService
{

    protected PaymentRepository $paymentRepository;
    protected PaymentPlanRepository $paymentPlanRepository;
    protected UserRepository $userRepository;
    protected UserProfileRepository $userProfileRepository;
    protected Paystack $paystackService;

    public function __construct(PaymentRepository $paymentRepository, PaymentPlanRepository $paymentPlanRepository, UserRepository $userRepository, Paystack $paystackService, UserProfileRepository $userProfileRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentPlanRepository = $paymentPlanRepository;
        $this->userRepository = $userRepository;
        $this->paystackService = $paystackService;
        $this->userProfileRepository = $userProfileRepository;
    }

    // pay/{plan_id}
    public function getPaymentLinkByPlanId(int $plan_id): Paystack
    {
        $amount = $this->paymentPlanRepository->getPlanAmountByPlanId($plan_id) * 100; // convert to kobo
        $user_id = Auth::id() ?? throw new UnauthorizedHttpException("", "You don't have authorization to access this page");
        $user = $this->userRepository->findOneBy('id', $user_id, ['email']);
        $reference = $this->paystackService->genTranxRef();


        $payment_link = $this->paystackService->getAuthorizationUrl([
            'amount' => $amount,
            'email' => $user['email'],
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
        ]);

        $payment = $this->paymentRepository->create([
            'status' => 'pending',
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'reference' => $reference,
            'amount' => $amount
        ]);

        return $payment_link;

    }

    // pay/verify/{reference}

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function verifyPayment(string $reference)
    {
        DB::beginTransaction();

        try {
            // Verify the transaction with Paystack
            $paymentDetails = $this->verifyPaystackPayment($reference);

            if (!$paymentDetails || $paymentDetails['status'] !== 'success')
                throw  ValidationException::withMessages(["Payment verification failed for reference: {$reference}."]);


            // Retrieve the payment record
            $payment = $this->paymentRepository->markPaymentAsPaid($reference);

            $duration = $this->paymentPlanRepository->getPlanDurationByPlanId($payment->plan_id);

            $expires_at = $this->userProfileRepository->updateExpiry($payment->user_id, $duration);

            DB::commit();

            return [
                'expires_at' => $expires_at,
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // payments
    public function getPaymentHistory(array|string|null $queryParams = null, int $perPage = 15)
    {
        return $this->paymentRepository->advancedCursorPaginate($queryParams, $perPage);
    }

    // payments/user
    public function getMyPaymentHistory(array|string|null $queryParams = null, int $perPage = 15)
    {
        return $this->paymentRepository->getUserPaymentHistory($queryParams, $perPage);
    }

    // payments/user/{user_id}
    public function getUserPaymentHistory(string|int $user_id, array|string|null $queryParams = null, int $perPage = 15)
    {
        return $this->paymentRepository->getUserPaymentHistory($queryParams, $perPage, $user_id);
    }

    public function getAllPaymentPlans()
    {
        return $this->paymentPlanRepository->all();
    }


    /**
     * @throws ValidationException
     */
    private function verifyPaystackPayment(string $reference): array
    {
        try {

            request()->merge(['trxref' => $reference]);
            return $this->paystackService->getPaymentData();
        } catch (PaymentVerificationFailedException $exception) {
            throw  ValidationException::withMessages([$exception->getMessage()]);
        }
    }


}
