<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    use HasUuids; // Fundamental porque nuestra llave primaria es UUID en el DDL

    // Aquí autorizamos las columnas seguras para inserción masiva
    protected $fillable = [
        'email',
        'customer_id',
        'is_active',
    ];

    /**
     * Relación con el cliente registrado.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}