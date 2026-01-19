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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        // ]);

        // Choose which seeder to run:
        // - FamilySeeder: Small demo family (30 people, 5 generations)
        // - FamilyTreeSeeder: Large comprehensive family tree (100 people, 8 generations)
        
        $this->call([
            // FamilySeeder::class,           // Comment this out if using FamilyTreeSeeder
            FamilyTreeSeeder::class,          // Uncomment to use the larger dataset
        ]);
    }
}
