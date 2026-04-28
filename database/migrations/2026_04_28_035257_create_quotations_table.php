<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->text('subject');
            $table->text('description');
            $table->text('attachments')->nullable()->comment('Array de rutas en Storage');
            $table->text('status')->default('pending');
            $table->decimal('estimated_price', 12, 2)->nullable();
            $table->text('response')->nullable();
            $table->timestampsTz();
        });
        DB::statement('COMMENT ON TABLE quotations IS \'Solicitudes de cotización de muebles a medida\'');
        DB::statement('COMMENT ON COLUMN quotations.status IS \'Estado: pending, reviewing, quoted, accepted, rejected\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};