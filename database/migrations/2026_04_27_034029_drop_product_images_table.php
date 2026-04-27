<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_images');
    }

    public function down(): void
    {
        // No recrearemos la tabla; si necesitas revertir, puedes recrear manualmente
        // o usar la migración original.
    }
};