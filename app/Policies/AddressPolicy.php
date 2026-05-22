<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
{
    /**
     * Determina si el usuario puede ver cualquier dirección.
     */
    public function viewAny(User $user): bool
    {
        // Solo clientes (usuarios con registro en customers)
        return $user->customer !== null;
    }

    /**
     * Determina si el usuario puede ver una dirección concreta.
     */
    public function view(User $user, Address $address): bool
    {
        return $user->customer && $address->customer_id === $user->customer->id;
    }

    /**
     * Determina si el usuario puede crear direcciones.
     */
    public function create(User $user): bool
    {
        return $user->customer !== null;
    }

    /**
     * Determina si el usuario puede actualizar la dirección.
     */
    public function update(User $user, Address $address): bool
    {
        return $this->view($user, $address);
    }

    /**
     * Determina si el usuario puede eliminar la dirección.
     */
    public function delete(User $user, Address $address): bool
    {
        return $this->view($user, $address);
    }

    /**
     * Determina si el usuario puede establecer una dirección como principal.
     */
    public function setPrimary(User $user, Address $address): bool
    {
        return $this->update($user, $address);
    }
}