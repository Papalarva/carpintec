<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'discounts';

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
        'type'       => DiscountType::class,
        'value'      => 'decimal:2',
        'starts_at'  => 'datetime',
        'ends_at'    => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_product', 'discount_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'discount_category', 'discount_id', 'category_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'discount_customer', 'discount_id', 'customer_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}