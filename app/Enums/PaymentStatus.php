<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
    case REFUNDED = 4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Pendiente',
            self::APPROVED => 'Aprobado',
            self::REJECTED => 'Rechazado',
            self::REFUNDED => 'Reembolsado',
        };
    }
}