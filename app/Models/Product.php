<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media; 

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

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

    // Colección de imágenes del producto
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Le decimos a Spatie que cree una copia optimizada en formato WebP
        $this->addMediaConversion('webp')
              ->format('webp')
              ->performOnCollections('product_images');
    }

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Inventario
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id');
    }

    // Accesor helper para la imagen de portada (la primera en orden)
    public function images()
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', 'product_images')
            ->orderBy('order_column');
    }

    public function coverImage()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'product_images')
            ->orderBy('order_column');
    }

    public function getImagesAttribute()
    {
        $images = $this->relationLoaded('images')
            ? $this->getRelation('images')
            : $this->images()->get();

        return $images->map(function (Media $media) {
            $media->setAttribute('webp_path', $this->mediaPath($media));

            return $media;
        });
    }

    public function getCoverImageAttribute()
    {
        $cover = $this->relationLoaded('coverImage')
            ? $this->getRelation('coverImage')
            : $this->coverImage()->first();

        if (!$cover) {
            return null;
        }

        $cover->setAttribute('webp_path', $this->mediaPath($cover));

        return $cover;
    }

    public function mediaUrl(?Media $media, string $conversion = 'webp'): ?string
    {
        if (!$media) {
            return null;
        }

        $path = $conversion !== '' && $media->hasGeneratedConversion($conversion)
            ? $media->getPath($conversion)
            : $media->getPath();

        if (!is_file($path)) {
            return null;
        }

        return $conversion !== '' && $media->hasGeneratedConversion($conversion)
            ? $media->getUrl($conversion)
            : $media->getUrl();
    }

    // Scopes (se mantienen)
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($term) {
            $inner->where('name', 'ilike', "%{$term}%")
                ->orWhere('short_description', 'ilike', "%{$term}%")
                ->orWhere('sku', 'ilike', "%{$term}%");
        });
    }

    // Ruta amigable con slug: {product}
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function (Product $product) {
            if (empty($product->id)) {
                $product->id = (string) Str::uuid();
            }

            if (empty($product->slug)) {
                $product->slug = static::uniqueSlugFrom($product->name);
            }
        });

        static::updating(function (Product $product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = static::uniqueSlugFrom($product->name, $product->id);
            }

            if ($product->isDirty('slug')) {
                $product->slug = static::uniqueSlugFrom($product->slug, $product->id);
            }
        });
    }

    protected static function uniqueSlugFrom(?string $value, ?string $ignoreId = null): string
    {
        $base = Str::slug((string) $value);

        if ($base === '') {
            $base = 'product';
        }

        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    protected function mediaPath(Media $media): string
    {
        return "{$media->id}/{$media->file_name}";
    }

    public function inventoryMovements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
}