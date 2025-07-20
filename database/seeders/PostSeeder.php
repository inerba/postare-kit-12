<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory(10)
            ->sequence(fn ($sequence) => [
                'author_id' => User::pluck('id')->random(),
                'category_id' => Category::pluck('id')->random(),
            ])
            ->create()
            ->each(function (Post $post) {
                $post->addMediaFromUrl('https://picsum.photos/1200/600')

                    ->toMediaCollection('featured_image');
            });
    }
}
