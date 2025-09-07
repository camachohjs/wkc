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
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'Super Admin']);
    }
}
