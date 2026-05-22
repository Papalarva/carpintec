<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    // En la tabla no hay updated_at, así que le decimos a Eloquent que no espere esa columna
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'webp_path',
        'sort_order',
        'is_cover',
    ];

    protected $casts = [
        'is_cover'   => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    // ──────────────────────
    // Relaciones
    // ──────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}