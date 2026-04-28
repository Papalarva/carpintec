<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
{
    /**
     * Determine if the user can view any addresses.
     */
    public function viewAny(User $user): bool
    {
        return $user->customer !== null;
    }

    /**
     * Determine if the user can view the address.
     */
    public function view(User $user, Address $address): bool
    {
        return $user->customer && $address->customer_id === $user->customer->id;
    }

    /**
     * Determine if the user can create addresses.
     */
    public function create(User $user): bool
    {
        return $user->customer !== null;
    }

    /**
     * Determine if the user can update the address.
     */
    public function update(User $user, Address $address): bool
    {
        return $this->view($user, $address);
    }

    /**
     * Determine if the user can delete the address.
     */
    public function delete(User $user, Address $address): bool
    {
        return $this->view($user, $address);
    }
}