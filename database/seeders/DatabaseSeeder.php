<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         
        \App\Models\User::create([
            'name' => 'Admin Jayra',
            'email' => 'admin@jayra.com',
            'password' => bcrypt('123456'),
            'rol' => 'admin'
        ]);

        \App\Models\User::create([
            'name' => 'María Cajera',
            'email' => 'cajera@jayra.com',
            'password' => bcrypt('123456'),
            'rol' => 'cajero'
        ]);

        
    }
    
}
