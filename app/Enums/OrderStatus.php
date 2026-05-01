<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;
    case CONFIRMED = 2;
    case PROCESSING = 3;
    case SHIPPED = 4;
    case DELIVERED = 5;
    case CANCELLED = 6;
    case REFUNDED = 7;

    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'Pendiente',
            self::CONFIRMED  => 'Confirmada',
            self::PROCESSING => 'En proceso',
            self::SHIPPED    => 'Enviada',
            self::DELIVERED  => 'Entregada',
            self::CANCELLED  => 'Cancelada',
            self::REFUNDED   => 'Reembolsada',
        };
    }
}