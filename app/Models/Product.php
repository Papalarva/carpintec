<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string|null $category_id
 * @property string $sku
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $materials
 * @property array<string, mixed>|null $dimensions
 * @property string $weight_kg
 * @property string $price
 * @property string|null $cost
 * @property bool $is_active
 * @property bool $is_customizable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Category|null $category
 * @property-read Collection<int, ProductImage> $images
 * @property-read Inventory|null $inventory
 * @property-read Collection<int, InventoryMovement> $movements
 * @property-read Collection<int, Discount> $discounts
 */
class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Campos permitidos para asignación masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
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

    /**
     * Conversión de atributos a tipos nativos de PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_customizable' => 'boolean',
            'dimensions' => 'array',
            'weight_kg' => 'decimal:2',
            'price' => 'decimal:2',
            'cost' => 'decimal:2',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Un producto pertenece a una categoría.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación 1:1 — Registro de inventario de este producto.
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Relación 1:N — Imágenes asociadas al producto, ordenadas por sort_order.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Relación 1:N — Movimientos de inventario de este producto.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Relación N:M — Descuentos directamente aplicados a este producto.
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_product');
    }
}
