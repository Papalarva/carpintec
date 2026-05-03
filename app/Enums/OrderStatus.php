<?php

namespace App\Enums;

enum OrderStatus: int 
{
    case PENDING    = 1;
    case PROCESSING = 2;
    case SHIPPED    = 3;
    case DELIVERED  = 4;
    case CANCELLED  = 5;
    case RETURNED   = 6;

    // 1. Método para el texto en español exacto de la BD
    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'Pendiente',
            self::PROCESSING => 'Procesando',
            self::SHIPPED    => 'En camino',
            self::DELIVERED  => 'Entregado',
            self::CANCELLED  => 'Cancelado',
            self::RETURNED   => 'Devuelto',
        };
    }

    // 2. Método para los colores del Badge
    public function color(): string
    {
        return match ($this) {
            self::PENDING    => 'yellow',
            self::PROCESSING => 'blue',
            self::SHIPPED    => 'purple',
            self::DELIVERED  => 'green',
            self::CANCELLED  => 'red',
            self::RETURNED   => 'gray',
        };
    }
}