<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
    }

    public function updateByReference(string $reference, array $data): int
    {
        return $this->model->newQuery()->where('reference', $reference)->update($data);
    }

    /**
     * @throws ValidationException
     */
    public function markPaymentAsPaid(string $reference)
    {
        // Retrieve the payment record
        $payment = $this->findOneByOrThrow('reference', $reference);

        if ($payment->status === 'completed')
            throw ValidationException::withMessages(["Payment with reference: {$reference} has already been completed."]);


        // Mark the payment as completed
        $payment->status = 'completed';
        $payment->paid_at = now();
        $payment->save();

        return $payment;
    }

    public function getUserPaymentHistory(array|string|null $queryParams = null, int $perPage = 15, string|null $user_id = null)
    {
        $userId = $user_id ?? Auth::id() ?? throw new UnauthorizedException("You don't have authorization to access this page.");

        $query = $this->customAdvancedCursorPaginate($queryParams, $perPage);

        $query->where('user_id', $userId);

        return $query->cursorPaginate($perPage);
    }

}
