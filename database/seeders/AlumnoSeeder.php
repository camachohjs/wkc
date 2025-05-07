<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\User;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(50)->create()->each(function ($user) {
            Alumno::factory()->create([
                'user_id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email
            ]);
        });
    }
}
