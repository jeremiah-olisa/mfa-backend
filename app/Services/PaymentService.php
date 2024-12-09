<?php

namespace App\Services;


use App\Repositories\PaymentPlanRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Unicodeveloper\Paystack\Exceptions\PaymentVerificationFailedException;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Validation\ValidationException;

class PaymentService extends Paystack
{

    protected PaymentRepository $paymentRepository;
    protected PaymentPlanRepository $paymentPlanRepository;
    protected UserRepository $userRepository;
    protected UserProfileRepository $userProfileRepository;

//    protected Paystack $paystackService;

    public function __construct(PaymentRepository $paymentRepository, PaymentPlanRepository $paymentPlanRepository, UserRepository $userRepository, UserProfileRepository $userProfileRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentPlanRepository = $paymentPlanRepository;
        $this->userRepository = $userRepository;
//        $this->paystackService = $paystackService;
        $this->userProfileRepository = $userProfileRepository;
        parent::__construct();
    }

    // pay/{plan_id}
    public function getPaymentLinkByPlanId(int $plan_id)
    {
        $amount = $this->paymentPlanRepository->getPlanAmountByPlanId($plan_id) * 100; // convert to kobo
        $user_id = Auth::id() ?? throw new UnauthorizedHttpException("", "You don't have authorization to access this page");
        $user = $this->userRepository->findOneBy('id', $user_id, ['email']);
        $reference = $this->genTranxRef();


        $paystackData = [
            'amount' => $amount,
            'email' => $user['email'],
            'reference' => $reference,
            'callback_url' => route('verifyPayment', ['reference' => $reference]),
            "metadata" => [
                "cancel_action" => "https://standard.paystack.co/close"
            ]
        ];
        $paystack = $this->getAuthorizationUrl($paystackData);

        $payment = $this->paymentRepository->create([
            'status' => 'pending',
            'user_id' => $user_id,
            'payment_plan_id' => $plan_id,
            'reference' => $reference,
            'amount' => $amount,
            'payment_method' => $paystack?->url ?? null,
        ]);

        return [
            'paystack' => $paystack,
            'payment' => $payment,
            'reference' => $reference,
            "metadata" => $paystackData["metadata"],
        ];

    }

    // pay/verify/{reference}

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function verifyPayment(string $reference)
    {
        // Verify the transaction with Paystack
        $paymentDetails = $this->verifyPaystackPayment($reference);

        if (!$paymentDetails || !$paymentDetails['status'])
            throw ValidationException::withMessages(["paystack" => "Payment verification failed for reference: {$reference}."]);

        DB::beginTransaction();
        try {
            // Retrieve the payment record
            $payment = $this->paymentRepository->markPaymentAsPaid($reference);

            $duration = $this->paymentPlanRepository->getPlanDurationByPlanId($payment->payment_plan_id);
            $payment_plan = $this->paymentPlanRepository->getPlanNameByPlanId($payment->payment_plan_id);

            $expires_at = $this->userProfileRepository->updateExpiry($payment->user_id, $duration, $payment_plan);

            DB::commit();

            return [
                'plan_expires_at' => $expires_at,
                'payment_plan_id' => $payment->payment_plan_id,
                'payment_plan' => $payment_plan,
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
            return $this->getPaymentData_v2($reference);
        } catch (PaymentVerificationFailedException $exception) {
            throw  ValidationException::withMessages([$exception->getMessage()]);
        } catch (ClientException  $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw ValidationException::withMessages([$message['message']]);
        } catch (\Exception|Throwable|HttpException $e) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            throw  new HttpException($statusCode, $e->getMessage(), $e);
        }
    }

    private function getPaymentData_v2($reference)
    {
        if ($this->isTransactionVerificationValid($reference)) {
            return $this->getResponse();
        } else {
            throw new PaymentVerificationFailedException("Invalid Transaction Reference");
        }
    }

    private function getResponse()
    {
        return json_decode($this->response->getBody(), true);
    }


}
