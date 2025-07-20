<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsAndCategoriesSeeder extends Seeder
{
    public function run(): void
    {

        // Truncate existing tags and categories to avoid duplicates
        $tags = [
            'Skincare',
            'Makeup',
            'Wellness',
            'Haircare',
            'Nutrizione',
            'Fitness',
            'Anti-age',
            'Dermatologia',
            'Cosmetici naturali',
            'Trattamenti estetici',
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

        $categories = [
            'Wellness',
            'Makeup',
            'Haircare',
            'Nutrizione',
            'Fitness',
            'Anti-age',
            'Moda',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
