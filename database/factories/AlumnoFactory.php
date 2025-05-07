<?php

namespace Database\Factories;

use App\Models\Alumno;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'nombre' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'fec' => $this->faker->date,
            'cinta' => $this->faker->randomElement(['Blanca', 'Morada', 'Amarilla', 'Naranja', 'Verde', 'Azul', 'Café']),
            'telefono' => $this->faker->phoneNumber,
            'peso' => $this->faker->randomFloat(2, 40, 120),
            'estatura' => $this->faker->randomFloat(2, 1, 2.5),
            'genero' => $this->faker->randomElement(['masculino', 'femenino']),
            'user_id' => function () use ($user) {
            return $user->id;
            },
        ];
    }
}
