<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Marca;

class ModeloFactory extends Factory
{
    public function definition(): array
    {
        return [
            'marca_id' => Marca::factory(),
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
