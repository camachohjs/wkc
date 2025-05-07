<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Torneo;

class TorneoFactory extends Factory
{
    protected $model = Torneo::class;
    
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'fecha_evento' => $this->faker->date,
            'direccion' => $this->faker->address,
            'banner' => $this->faker->imageUrl(),
            'ranking' => $this->faker->numberBetween(1, 100),
        ];
    }
}
