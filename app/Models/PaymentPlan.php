<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relationship with Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
