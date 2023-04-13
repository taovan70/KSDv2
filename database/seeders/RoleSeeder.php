<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = array_keys(config('roles_seeding.values'));

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'default_role' => $role == config('roles_seeding.default_role')]);
        }
    }
}
