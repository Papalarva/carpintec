<?php

namespace App\Enums;

enum QuotationStatus: string
{
    case PENDING   = 'pending';
    case REVIEWING = 'reviewing';
    case QUOTED    = 'quoted';   // <-- Nuevo estado oficial
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'Pendiente',
            self::REVIEWING => 'En revisión',
            self::QUOTED    => 'Cotizada',   // <-- Su etiqueta en español
            self::APPROVED  => 'Aprobada',
            self::REJECTED  => 'Rechazada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING   => 'yellow',
            self::REVIEWING => 'blue',
            self::QUOTED    => 'purple',     // <-- Un color distinto (purple o indigo)
            self::APPROVED  => 'green',
            self::REJECTED  => 'red',
        };
    }
}