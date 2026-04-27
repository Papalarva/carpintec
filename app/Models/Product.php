<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'track_inventory',
        'sku',
        'name',
        'slug',
        'short_description',
        'long_description',
        'materials',
        'dimensions',
        'weight_kg',
        'price',
        'cost',
        'is_active',
        'is_customizable',
    ];

    protected $casts = [
        'track_inventory' => 'boolean',
        'is_active'       => 'boolean',
        'is_customizable' => 'boolean',
        'price'           => 'decimal:2',
        'cost'            => 'decimal:2',
        'weight_kg'       => 'decimal:2',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    // ──────────────────────
    // Relaciones
    // ──────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    // Imagen de portada (la marcada is_cover = true)
    public function coverImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_cover', true);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id');
    }

    // ──────────────────────
    // Scopes para catálogo público
    // ──────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'ilike', "%{$term}%")
              ->orWhere('short_description', 'ilike', "%{$term}%")
              ->orWhere('sku', 'ilike', "%{$term}%");
        });
    }

    public function scopePriceBetween($query, $min, $max)
    {
        if (!is_null($min)) {
            $query->where('price', '>=', $min);
        }
        if (!is_null($max)) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    // ──────────────────────
    // Auto-slug
    // ──────────────────────
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}