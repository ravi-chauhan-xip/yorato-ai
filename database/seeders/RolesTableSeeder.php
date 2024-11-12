<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'admin',
        ]);

        Role::create([
            'name' => 'member',
            'guard_name' => 'member',
        ]);
    }
}
