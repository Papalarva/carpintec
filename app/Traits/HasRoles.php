<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Relación: Un modelo tiene muchos roles a través de la tabla pivote.
     */
    public function roles(): BelongsToMany
    {
        // ELIMINAMOS la línea ->wherePivot(...) para evitar el problema de las diagonales
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    /**
     * Verifica si el usuario tiene un rol específico o uno dentro de un arreglo.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        return (bool) $this->roles->whereIn('name', $roles)->count();
    }
}