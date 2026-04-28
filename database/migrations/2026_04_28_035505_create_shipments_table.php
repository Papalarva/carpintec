<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('address_id');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->text('shipping_method');
            $table->text('carrier')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('tracking_number')->nullable();
            $table->text('label_url')->nullable();
            $table->text('status')->default('pending');
            $table->jsonb('api_response')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestampsTz();
        });
        DB::statement('COMMENT ON TABLE shipments IS \'Información de envío de pedidos\'');
        DB::statement('COMMENT ON COLUMN shipments.api_response IS \'Respuesta cruda de la API de paquetería (fallback)\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};