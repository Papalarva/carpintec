<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'inventory'; // ← ¡esto es clave!

    protected $fillable = [
        'product_id',
        'quantity',
        'min_quantity',
        'location',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'min_quantity' => 'integer',
        'updated_at'   => 'datetime',
    ];

    // ──────────────────────
    // Relaciones
    // ──────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ──────────────────────
    // Helpers
    // ──────────────────────

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }
}