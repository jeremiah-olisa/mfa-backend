<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
