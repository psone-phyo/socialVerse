<?php

namespace Database\Seeders;

use App\Models\City;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        School::factory(5)->create();
        // User::factory()->create([
        //     'name' => 'Pyae Sone Phyo',
        //     'email' => 'superadmin63@gmail.com',
        //     'password' => Hash::make('pyaesone6325'),
        //     'role' => 'superadmin'
        // ]);
    }
}
