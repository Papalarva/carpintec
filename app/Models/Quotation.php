<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- Vital para UUIDs
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory, HasUuids; // <-- Añadido

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'product_id', // <-- Agregado para saber qué mueble inspiró la cotización
        'subject',
        'description',
        'attachments',
        'status',
        'estimated_price',
        'response',
    ];

    protected $casts = [
        'attachments' => 'array',
        'estimated_price' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Comprobación de estado para la UI y validaciones futuras
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isQuoted(): bool { return $this->status === 'quoted'; }
}