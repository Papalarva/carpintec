<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->text('name')->unique();
        });

        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => 'pending'],
            ['id' => 2, 'name' => 'confirmed'],
            ['id' => 3, 'name' => 'processing'],
            ['id' => 4, 'name' => 'shipped'],
            ['id' => 5, 'name' => 'delivered'],
            ['id' => 6, 'name' => 'cancelled'],
            ['id' => 7, 'name' => 'returned'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};