<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Marca;

class MarcaFactory extends Factory
{
        protected $model = Marca::class; 

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
