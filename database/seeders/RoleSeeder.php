<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'permissions' => json_encode(['all'])
            ],
            [
                'name' => 'editor',
                'permissions' => json_encode(['posts.create', 'posts.edit', 'posts.delete', 'comments.moderate'])
            ],
            [
                'name' => 'author',
                'permissions' => json_encode(['posts.create', 'posts.edit_own', 'posts.delete_own'])
            ],
            [
                'name' => 'reader',
                'permissions' => json_encode(['posts.read', 'comments.create'])
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}