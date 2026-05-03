<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'coupons';

    // 👇 LA CORRECCIÓN: Dejamos que Laravel llene created_at, pero ignoramos updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'code',
        'discount_id',
        'max_uses',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'max_uses'   => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}