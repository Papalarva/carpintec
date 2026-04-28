<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'is_active',
        'applies_to',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_product');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'discount_category');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'discount_customer');
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    // Scopes útiles para verificar vigencia
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where(function ($q) use ($now) {
                         $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                     })
                     ->where(function ($q) use ($now) {
                         $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                     });
    }
}