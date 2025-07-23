<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the RoleSeeder to create roles
        $this->call(RoleSeeder::class);

        // Check if the 'admin' role already exists before creating it
        if (! Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Check if the 'teacher' role already exists before creating it
        if (! Role::where('name', 'teacher')->exists()) {
            Role::create(['name' => 'teacher']);
        }
    }
}
