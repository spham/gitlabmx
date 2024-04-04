<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Amitav Roy',
            'email' => 'admin@admin.com',
            // 'password' => bcrypt('Password@123'),
            'gitlab_id' => 56232356,
        ]);

        // Project::factory()->count(20)->create();
    }
}
