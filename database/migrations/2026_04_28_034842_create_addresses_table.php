<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->text('alias')->nullable();
            $table->text('street');
            $table->text('exterior_number');
            $table->text('interior_number')->nullable();
            $table->text('neighborhood');
            $table->text('city');
            $table->text('state');
            $table->text('postal_code');
            $table->text('country')->default('México');
            $table->text('contact_phone')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestampsTz();

            // Índice único parcial para garantizar una sola dirección principal por cliente
            DB::statement('CREATE UNIQUE INDEX unique_primary_address_per_customer ON addresses (customer_id) WHERE is_primary IS TRUE');
        });

        DB::statement('COMMENT ON TABLE addresses IS \'Direcciones de envío/facturación de clientes\'');
        DB::statement('COMMENT ON COLUMN addresses.is_primary IS \'Indica si es la dirección predeterminada\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};