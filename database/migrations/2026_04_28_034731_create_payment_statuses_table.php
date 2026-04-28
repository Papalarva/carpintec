<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text('name')->unique();
        });

        DB::table('payment_statuses')->insert([
            ['id' => 1, 'name' => 'pending'],
            ['id' => 2, 'name' => 'approved'],
            ['id' => 3, 'name' => 'rejected'],
            ['id' => 4, 'name' => 'refunded'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_statuses');
    }
};