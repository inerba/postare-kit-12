<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TagsAndCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $tags = ['Laravel', 'Vue.js', 'Tailwindcss', 'Inertia', 'Breeze', 'Jetstream', 'Fortify', 'Scout', 'Sanctum', 'Socialite'];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

        $categories = ['News', 'Tips', 'Tutorials', 'Updates', 'Packages', 'Podcast', 'Videos'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
