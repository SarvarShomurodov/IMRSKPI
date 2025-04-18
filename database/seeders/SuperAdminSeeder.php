<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'firstName' => 'Sarvar',
            'lastName' => 'Shomurodov', 
            'email' => 'sarvar9818sh@gmail.com',
            'position' => 'Yetakchi mutaxasis',
            'password' => Hash::make('password')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'firstName' => 'Admin',
            'lastName' => 'admin', 
            'email' => 'admin@gmail.com',
            'position' => 'Yetakchi mutaxasis',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('Admin');

        // Creating Product Manager User
        $productManager = User::create([
            'firstName' => 'Rustam',
            'lastName' => 'Odlilov', 
            'email' => 'rustam@gmail.com',
            'position' => 'Yetakchi mutaxasis',
            'password' => Hash::make('password')
        ]);
        $productManager->assignRole('User');
    }
}