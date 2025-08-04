<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Teknoloji', 'description' => 'Teknoloji ile ilgili yazılar', 'color' => '#007bff'],
            ['name' => 'Yazılım', 'description' => 'Yazılım geliştirme konuları', 'color' => '#28a745'],
            ['name' => 'Laravel', 'description' => 'Laravel framework hakkında', 'color' => '#dc3545'],
            ['name' => 'PHP', 'description' => 'PHP programlama dili', 'color' => '#ffc107'],
            ['name' => 'Web Tasarım', 'description' => 'Web tasarım ve UI/UX', 'color' => '#17a2b8'],
            ['name' => 'Veritabanı', 'description' => 'Veritabanı yönetimi', 'color' => '#6f42c1'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color' => $category['color']
            ]);
        }
    }
}