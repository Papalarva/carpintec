<?php

namespace App\Enums;

enum DiscountType: string
{
    case PERCENTAGE   = 'percentage';
    case FIXED_AMOUNT = 'fixed_amount';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE   => 'Porcentaje',
            self::FIXED_AMOUNT => 'Monto fijo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PERCENTAGE   => 'blue',
            self::FIXED_AMOUNT => 'green',
        };
    }
}