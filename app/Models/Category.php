<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasFactory, HasUuids;

    // Clave primaria UUID (no autoincremental)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ──────────────────────
    // Relaciones
    // ──────────────────────

    // Categoría padre (autoreferencia)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Subcategorías (hijos)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Productos de esta categoría
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ──────────────────────
    // Scopes útiles para el catálogo
    // ──────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // ──────────────────────
    // Auto-generar slug al crear/actualizar
    // ──────────────────────
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }


}