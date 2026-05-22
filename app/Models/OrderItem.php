<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false; // (Si tu tabla no tiene updated_at)

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'unit_discount',
        'inventory_movement_id', // <-- ¡Asegúrate de que esta línea exista!
    ];

    protected $casts = [
        'unit_price'    => 'decimal:2',
        'unit_discount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryMovement()
    {
        return $this->belongsTo(InventoryMovement::class);
    }
}