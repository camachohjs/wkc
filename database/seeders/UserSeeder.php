<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [
            // Permisos Admin
            'gestionas usuarios',
            'gestiones roles',
            'gestionar torneos',
            'gestionar inscripciones',
            'gestionar resultados',

            // Permisos Supervisor
            'registrar puntuaciones',

            //Permisos Maestros
            'registrar alumnos',
            'inscribir alumnos a torneos',
            'ver resultados de alumnos',

            //Permissos Alumno
            'ver perfil ',
            'editar perfil',
            'ver torneos',
            'inscribirse a torneos',
            've resultados propios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        //Roles 
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $maestro = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $alumno = Role::firstOrCreate(['name' => 'alumno', 'guard_name' => 'web']);

        //Asignacionde permisos
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'gestionas usuarios',
            'gestiones roles',
            'gestionar torneos',
            'gestionar inscripciones',
            'gestionar resultados',
        ]);

        $supervisor->givePermissionTo([
            'registrar puntuaciones',
        ]);

        $maestro->givePermissionTo([
            'registrar alumnos',
            'inscribir alumnos a torneos',
            'ver resultados de alumnos',
        ]);

        $alumno->givePermissionTo([
            'ver perfil ',
            'editar perfil',
            'ver torneos',
            'inscribirse a torneos',
            've resultados propios',
        ]);

        $superAdmin = User::firstOrCreate(
            ['email' => 'jsanchez@rkt.lat'],
            [
                'name' => 'Jesus Sanchez',
                'password' => Hash::make('J54nch3z198212*'),
            ]
        );

        // Asignar rol super-admin si aún no lo tiene
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        //Administrador 
        $admin = User::firstOrCreate(
            ['email' => 'admin@rkt.lat'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('zJ05$7yOkywg')
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
