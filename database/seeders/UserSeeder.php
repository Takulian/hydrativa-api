<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "admin",
            "email" => "saep@gmail.com",
            "password" => "rahasia",
            "name" =>  "Saep Jelek",
            "telp" =>  "088176358631",
            "jenis_kelamin" => "Laki-laki",
            'role' => 1
        ]);
    }
}
