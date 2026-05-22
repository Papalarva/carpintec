<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory, HasUuids;

    // 👇 AQUÍ ESTÁ LA MAGIA: Le decimos a Laravel que ignore el updated_at 👇
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'resulting_quantity',
        'reference',
        'user_id',
    ];

    protected $casts = [
        'quantity'           => 'integer',
        'resulting_quantity' => 'integer',
    ];

    public const TYPE_SALE      = 'sale';
    public const TYPE_RESTOCK   = 'restock';
    public const TYPE_ADJUSTMENT = 'adjustment';
    public const TYPE_RETURN    = 'return';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}