<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // admin
            'bio' => 'Site yöneticisi'
        ]);

        // Editor user
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // editor
            'bio' => 'İçerik editörü'
        ]);

        // Author user
        User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // author
            'bio' => 'Blog yazarı'
        ]);

        // Regular users
        User::factory(10)->create();
    }
}