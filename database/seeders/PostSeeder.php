<?php

namespace Database\Seeders;

use Blade;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory(38)
            ->sequence(fn($sequence) => [
                'author_id' => User::pluck('id')->random(),
                'category_id' => Category::pluck('id')->random()
            ])
            ->create()
            ->each(function (Post $post) {
                $post->addMediaFromUrl('https://picsum.photos/600/800')->toMediaCollection('featured_image');
            });

        Post::factory()
            ->sequence(fn($sequence) => [
                'author_id' => User::pluck('id')->random(),
                'category_id' => Category::pluck('id')->random()
            ])
            ->create([
                'content' => Blade::render(file_get_contents(resource_path('test_content/content.blade.php'))),
                'title' => 'Forge: Zero Downtime Deployments',
            ])
            ->addMediaFromUrl('https://picsum.photos/600/800')->toMediaCollection('featured_image');
    }
}
