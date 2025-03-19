<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transac_id',
        'package_code',
        'payer_email',
        'payer_id',
        'payer_country',
        'amount',
        'currency',
        'status',
        'recurring_duration',
        'next_billing_date',
        'last_payment_date',
        'is_active',
        'cancel_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_billing_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'is_active' => 'boolean',
    ];

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

}