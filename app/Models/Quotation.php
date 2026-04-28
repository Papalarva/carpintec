<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'subject',
        'description',
        'attachments',
        'status',
        'estimated_price',
        'response',
    ];

    protected $casts = [
        'attachments' => 'array', // Postgres text[] -> array
        'estimated_price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Método para convertir cotización aprobada en pedido (se usará más adelante)
    // Lo implementaremos cuando corresponda.
}