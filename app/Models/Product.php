<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $category_id
 * @property bool $track_inventory
 * @property string $sku
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $materials
 * @property string|null $dimensions
 * @property string|null $weight_kg
 * @property string $price
 * @property string $cost
 * @property bool $is_active
 * @property bool $is_customizable
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Category|null $category
 */
class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Conversión de atributos a tipos nativos de PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
            'is_customizable' => 'boolean',
            'weight_kg' => 'decimal:2',
            'price' => 'decimal:2',
            'cost' => 'decimal:2',
        ];
    }

    /**
     * Relación N:1 — Un producto pertenece a una categoría.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
