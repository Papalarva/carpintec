<?php

namespace App\Models;

use App\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quotation extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;

    protected $table = 'quotations';

    protected $fillable = [
        'customer_id',
        'product_id',
        'subject',
        'description',
        'estimated_price',
        'response',
        'status',
    ];

    protected $casts = [
        'status'          => QuotationStatus::class,
        'estimated_price' => 'decimal:2',
        'attachments'     => 'json', // ignoraremos en la lógica, pero mantenemos el cast por si acaso
    ];

    // ──────────────────────
    // Relaciones
    // ──────────────────────
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    // ──────────────────────
    // Media Library
    // ──────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('quotation_files')
             ->useDisk('local'); // privado, como exige la regla
    }

    // ──────────────────────
    // Scopes útiles
    // ──────────────────────
    public function scopePending($query)
    {
        return $query->where('status', QuotationStatus::PENDING);
    }

    public function messages()
    {
        return $this->hasMany(QuotationMessage::class)->orderBy('created_at', 'asc');
    }
}