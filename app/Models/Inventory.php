<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // La clave primaria es 'product_id', no 'id'
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // En la tabla solo existe updated_at, no created_at
    const CREATED_AT = null;

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
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ──────────────────────
    // Helpers
    // ──────────────────────

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }
}