<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'customer_id',
        'shipping_address_id',
        'shipment_id',
        'quotation_id',
        'coupon_id',
        'status_id',
        'subtotal',
        'discount_total',
        'shipping_cost',
        'total',
        'notes',
    ];

    protected $casts = [
        'status_id'      => OrderStatus::class,
        'subtotal'       => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_cost'  => 'decimal:2',
        'total'          => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}