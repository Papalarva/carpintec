<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('quotation_id')->constrained()->cascadeOnDelete();
            $table->enum('sender_type', ['customer', 'admin']);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_messages');
    }
};
