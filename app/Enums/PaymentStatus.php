<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING  = 1;
    case PAID     = 2;
    case REJECTED = 3;
    case REFUNDED = 4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Pendiente',
            self::PAID     => 'Pagado',
            self::REJECTED => 'Rechazado',
            self::REFUNDED => 'Reembolsado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING  => 'yellow',
            self::PAID     => 'green',
            self::REJECTED => 'red',
            self::REFUNDED => 'orange',
        };
    }
}