<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular User
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Sample Posts
        Post::create([
            'user_id' => $admin->id,
            'title' => 'First Blog Post by Admin',
            'content' => 'This is a sample post created by the admin.',
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Learning Laravel and Vue.js',
            'content' => 'This is a simple blog post created by a regular user.',
        ]);
    }
}
