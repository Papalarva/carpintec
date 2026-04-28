<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('code')->unique();
            $table->uuid('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestampTz('expires_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
        DB::statement('COMMENT ON TABLE coupons IS \'Cupones de descuento por código\'');
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};