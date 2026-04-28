<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->smallInteger('status_id');
            $table->foreign('status_id')->references('id')->on('payment_statuses');
            $table->text('mp_transaction_id')->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->jsonb('mp_data')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->timestampsTz();
        });
        DB::statement('COMMENT ON TABLE payments IS \'Registro de pagos asociados a pedidos\'');
        DB::statement('COMMENT ON COLUMN payments.mp_data IS \'Objeto JSON con toda la respuesta de Mercado Pago\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};