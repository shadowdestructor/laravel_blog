<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'content' => $this->faker->paragraph(),
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'post_id' => Post::inRandomOrder()->first()->id ?? 1,
            'parent_id' => $this->faker->optional(0.2)->randomElement(Comment::pluck('id')->toArray()),
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function approved()
    {
        return $this->state(fn() => ['status' => 'approved']);
    }
}