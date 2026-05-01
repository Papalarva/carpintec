<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrador del sistema'],
            ['name' => 'worker', 'description' => 'Trabajador / operador'],
            ['name' => 'customer', 'description' => 'Cliente'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'id'          => Str::uuid(),
                'name'        => $role['name'],
                'description' => $role['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // Estados de pedido
        foreach (OrderStatus::cases() as $status) {
            DB::table('order_statuses')->insert([
                'id'   => $status->value,
                'name' => $status->name,
            ]);
        }

        // Estados de pago
        foreach (PaymentStatus::cases() as $status) {
            DB::table('payment_statuses')->insert([
                'id'   => $status->value,
                'name' => $status->name,
            ]);
        }

        // Superusuario admin
        $admin = User::create([
            'id'                => Str::uuid(),
            'first_name'        => 'Admin',
            'last_name'         => 'Principal',
            'email'             => 'admin@mueblespremium.test',
            'password'          => Hash::make('password'), // cambiar en producción
            'email_verified_at' => now(),
        ]);

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        DB::table('model_has_roles')->insert([
            'role_id'    => $adminRoleId,
            'model_type' => User::class,
            'model_id'   => $admin->id,
        ]);
    }
}