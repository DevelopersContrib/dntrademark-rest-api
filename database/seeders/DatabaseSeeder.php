<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Package;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Package::truncate();

        Package::create([
            'name' => 'Free Package',
            'start_limit' => 0,
            'end_limit' => 1,
            'price' => 0.00,
        ]);
        Package::create([
            'name' => 'Package A',
            'start_limit' => 1,
            'end_limit' => 100,
            'price' => 9.88,
        ]);
        Package::create([
            'name' => 'Package B',
            'start_limit' => 101,
            'end_limit' => 500,
            'price' => 48.88,
        ]);
        Package::create([
            'name' => 'Package C',
            'start_limit' => 501,
            'end_limit' => 2000,
            'price' => 98.88,
        ]);
        Package::create([
            'name' => 'Package D',
            'start_limit' => 2001,
            'end_limit' => 4000,
            'price' => 198.88,
        ]);
    }
}
