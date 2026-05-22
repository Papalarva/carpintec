<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function view(User $user, Quotation $quotation): bool
    {
        return $user->customer && $quotation->customer_id === $user->customer->id;
    }

    public function create(User $user): bool
    {
        return $user->customer !== null;
    }

    // Otros métodos según necesidad...
}