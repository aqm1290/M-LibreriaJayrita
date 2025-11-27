<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::factory(50)->create();
    }
}
