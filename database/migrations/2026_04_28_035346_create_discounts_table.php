<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('name');
            $table->text('type'); // 'percentage' o 'fixed_amount'
            $table->decimal('value', 12, 2);
            $table->timestampTz('starts_at')->nullable();
            $table->timestampTz('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('applies_to'); // 'product', 'category', 'customer', 'general'
            $table->timestampsTz();
        });

        Schema::create('discount_product', function (Blueprint $table) {
            $table->uuid('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['discount_id', 'product_id']);
        });

        Schema::create('discount_category', function (Blueprint $table) {
            $table->uuid('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->primary(['discount_id', 'category_id']);
        });

        Schema::create('discount_customer', function (Blueprint $table) {
            $table->uuid('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->primary(['discount_id', 'customer_id']);
        });

        DB::statement('COMMENT ON TABLE discounts IS \'Reglas de descuento flexibles\'');
        DB::statement('COMMENT ON COLUMN discounts.type IS \'percentage o fixed_amount\'');
        DB::statement('COMMENT ON COLUMN discounts.applies_to IS \'Ámbito de aplicación: producto, categoría, cliente, general\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_customer');
        Schema::dropIfExists('discount_category');
        Schema::dropIfExists('discount_product');
        Schema::dropIfExists('discounts');
    }
};