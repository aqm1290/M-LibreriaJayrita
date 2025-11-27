<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'       => $this->faker->sentence(3),
            'descripcion'  => $this->faker->sentence(),
            'precio'       => $this->faker->randomFloat(2, 10, 500),
            'costo_compra' => $this->faker->randomFloat(2, 5, 300),
            'stock'        => $this->faker->numberBetween(1, 100),
            'url_imagen'   => null,
            'color'        => $this->faker->safeColorName(),
            'tipo'         => $this->faker->word(),
            'codigo'       => $this->faker->unique()->bothify('COD-####'), 
            'categoria_id' => \App\Models\Categoria::inRandomOrder()->first()->id,
            'marca_id'     => \App\Models\Marca::inRandomOrder()->first()->id,
            'modelo_id'    => \App\Models\Modelo::inRandomOrder()->first()->id,
            'promo_id'     => null,
        ];
    }
}
