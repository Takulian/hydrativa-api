<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            'nama_role' => 'Admin'
        ]);
        Role::insert([
            'nama_role' => 'Petani'
        ]);
        Role::insert([
            'nama_role' => 'Costumer'
        ]);
    }
}
