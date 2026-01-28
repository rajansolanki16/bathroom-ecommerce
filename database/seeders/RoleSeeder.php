<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Delete existing roles
        Role::whereIn('name', ['admin', 'vendor', 'salesman'])->delete();

        // Create roles with 'web' guard
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'vendor', 'guard_name' => 'web']);
        Role::create(['name' => 'salesman', 'guard_name' => 'web']);
    }
}
