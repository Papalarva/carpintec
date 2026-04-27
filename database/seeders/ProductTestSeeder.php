<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTestSeeder extends Seeder
{
    public function run(): void
    {
        // Crear categoría con UUID explícito
        $category = Category::firstOrCreate(
            ['slug' => 'sillas'],
            [
                'id'          => (string) Str::uuid(),
                'name'        => 'Sillas',
                'is_active'   => true,
            ]
        );

        $product = Product::create([
            'id' => (string) Str::uuid(),
            'category_id' => $category->id,
            'sku' => 'SILLA-001',
            'name' => 'Silla de comedor clásica',
            'slug' => 'silla-comedor-clasica',
            'short_description' => 'Silla de comedor hecha a mano con madera de roble.',
            'price' => 2500.00,
            'is_customizable' => false,
            'track_inventory' => true,
        ]);

        // Subir imagen de prueba (debes tener un archivo local)
        $product->addMedia(storage_path('app/public/silla.jpg'))
        ->toMediaCollection('product_images');
    }
}