<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->uuid('shipping_address_id');
            $table->foreign('shipping_address_id')->references('id')->on('addresses');
            $table->uuid('shipment_id')->nullable();
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations');
            $table->uuid('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->smallInteger('status_id');
            $table->foreign('status_id')->references('id')->on('order_statuses');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->text('notes')->nullable();
            $table->timestampsTz();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('quantity')->check('quantity > 0');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('unit_discount', 12, 2)->default(0);
            $table->uuid('inventory_movement_id')->nullable();
            $table->foreign('inventory_movement_id')->references('id')->on('inventory_movements');
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->smallInteger('status_id');
            $table->foreign('status_id')->references('id')->on('order_statuses');
            $table->text('comment')->nullable();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestampTz('changed_at')->useCurrent();
        });

        // Índices
        Schema::table('orders', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('status_id');
            // Índice único parcial: una cotización solo puede convertirse en un pedido
            DB::statement('CREATE UNIQUE INDEX idx_orders_quotation_unique ON orders (quotation_id) WHERE quotation_id IS NOT NULL');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};