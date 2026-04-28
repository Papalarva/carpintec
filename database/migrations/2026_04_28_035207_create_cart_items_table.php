<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('cart_id');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->check('quantity > 0');
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['cart_id', 'product_id']);
        });
        DB::statement('COMMENT ON TABLE cart_items IS \'Productos agregados al carrito\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};