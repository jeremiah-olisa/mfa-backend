<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentPlan;

class PaymentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentPlans = [
            [
                'name' => 'Daily Plan',
                'amount' => 100.00,
                'duration' => 1, // 1 day
                'description' => 'Access to premium features for a single day.',
            ],
            [
                'name' => 'Weekly Plan',
                'amount' => 500.00,
                'duration' => 7, // 7 days
                'description' => 'Access to premium features for a week.',
            ],
            [
                'name' => 'Monthly Plan',
                'amount' => 1200.00,
                'duration' => 31, // 31 days
                'description' => 'Access to premium features for a month.',
            ],
            [
                'name' => 'Yearly Plan',
                'amount' => 14400.00,
                'duration' => 366, // 366 days
                'description' => 'Access to premium features for a year.',
            ],
        ];

        foreach ($paymentPlans as $plan) {
            PaymentPlan::create($plan);
        }
    }
}
