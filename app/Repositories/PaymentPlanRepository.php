<?php

namespace App\Repositories;

use App\Models\PaymentPlan;
use App\Repositories\BaseRepository;

class PaymentPlanRepository extends BaseRepository
{
    public function __construct(PaymentPlan $paymentPlan)
    {
        parent::__construct($paymentPlan);
    }

    public function getPlanAmountByPlanId(int $plan_Id): float
    {
        return $this->model->newQuery()->where('id', $plan_Id)->valueOrFail('amount');
    }

    public function getPlanDurationByPlanId(int $plan_Id): int
    {
        return $this->model->newQuery()->where('id', $plan_Id)->valueOrFail('duration');
    }

    public function getPlanNameByPlanId(int $plan_Id): string
    {
        return $this->model->newQuery()->where('id', $plan_Id)->valueOrFail('name');
    }
}
