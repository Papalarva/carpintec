<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'status_id',
        'mp_transaction_id',
        'amount',
        'mp_data',
        'paid_at',
    ];

    protected $casts = [
        'status_id' => PaymentStatus::class,
        'amount'    => 'decimal:2',
        'mp_data'   => 'array',
        'paid_at'   => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}