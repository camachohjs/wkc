<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Asegurarse de que el rol de administrador existe
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Buscar el usuario existente por ID o correo electrónico
        $admin = User::where('id', '84')->first();

        // Verificar si el usuario existe
        if ($admin) {
            // Asignar el rol de administrador al usuario existente
            $admin->assignRole($role);
        }
    }
}
