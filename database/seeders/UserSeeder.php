<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'ahdirmai@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // Use a secure password
            ]
        );
        // Assign the admin role to the user
        $admin->assignRole('admin');

        // Create a regular user
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'ridhofahmij225@gmail.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'), // Use a secure password
            ]
        );
        // Assign the user role to the user
        $user->assignRole('user');
    }
}
