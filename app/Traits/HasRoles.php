<?php

namespace App\Traits;

use App\Models\Role;
// 1. Cambiamos el import a MorphToMany
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRoles
{
    /**
     * Relación: Un modelo tiene muchos roles a través de la tabla pivote polimórfica.
     */
    public function roles(): MorphToMany // 2. Actualizamos el tipo de retorno
    {
        // 3. Cambiamos belongsToMany por morphToMany
        // El segundo parámetro 'model' le indica a Laravel que las columnas se llaman 'model_id' y 'model_type'
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
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