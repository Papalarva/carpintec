<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

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
        'cost' => 'decimal:2',
        'api_response' => 'array',
        'estimated_delivery_date' => 'date',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}