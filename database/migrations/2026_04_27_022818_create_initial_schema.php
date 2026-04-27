<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Todas las tablas ya existen en Supabase; no creamos nada
        // Solo registramos esta migración para control de versiones
    }

    public function down(): void
    {
        // No se debe eliminar el esquema existente; se deja vacío
    }
};