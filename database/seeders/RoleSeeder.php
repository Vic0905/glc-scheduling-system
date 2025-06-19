<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
   
        public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);
    }
}

