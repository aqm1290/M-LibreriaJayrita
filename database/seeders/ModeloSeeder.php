<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modelo;
use App\Models\Marca;

class ModeloSeeder extends Seeder
{
    public function run(): void
    {
        // 3 modelos por marca
        Marca::all()->each(function ($marca) {
            Modelo::factory(3)->create([
                'marca_id' => $marca->id
            ]);
        });
    }
}