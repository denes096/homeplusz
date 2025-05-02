<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@otthonplusz.hu',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@otthonplusz.hu',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'referens@otthonplusz.hu',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@otthonplusz.hu',
            'password' => Hash::make('password'),
        ]);
    }
}
