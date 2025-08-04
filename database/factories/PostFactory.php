<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition()
    {
        $title = $this->faker->sentence(6, true);
        $content = $this->faker->paragraphs(rand(5, 15), true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $content,
            'excerpt' => Str::limit(strip_tags($content), 200),
            'status' => $this->faker->randomElement(['published', 'draft', 'pending']),
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'views_count' => $this->faker->numberBetween(0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function published()
    {
        return $this->state(fn() => ['status' => 'published']);
    }

    public function draft()
    {
        return $this->state(fn() => ['status' => 'draft']);
    }
}