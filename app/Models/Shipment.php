<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'address_id',
        'shipping_method',
        'carrier',
        'cost',
        'tracking_number',
        'label_url',
        'status',
        'api_response',
        'estimated_delivery_date',
    ];

    protected $casts = [
        'cost'             => 'decimal:2',
        'api_response'     => 'array',
        'estimated_delivery_date' => 'date',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}