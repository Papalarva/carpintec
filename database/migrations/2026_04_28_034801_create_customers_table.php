<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('birth_date')->nullable();
            $table->boolean('accepts_marketing')->default(false);
            $table->timestampsTz();
        });
        DB::statement('COMMENT ON TABLE customers IS \'Información adicional específica de clientes\'');
        DB::statement('COMMENT ON COLUMN customers.accepts_marketing IS \'Acepta recibir comunicaciones de marketing\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};