<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('customer_id')->unique();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestampTz('updated_at')->useCurrent();
        });
        DB::statement('COMMENT ON TABLE carts IS \'Carrito de compras activo por cliente (uno por cliente)\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};