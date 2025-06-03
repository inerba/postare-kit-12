<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'content' => json_decode('{"type":"doc","content":[{"type":"masonBrick","attrs":{"identifier":"block","values":{"header_title":null,"header_align":"center","header_tagline":null,"dropcap":true,"content":"<p>Donec et urna vel risus feugiat pharetra. Proin id lacus vitae velit accumsan venenatis. Aenean non mi vel nisi lacinia maximus. Duis efficitur, sapien quis bibendum auctor, lectus risus feugiat sapien, ac pulvinar orci est a arcu. Integer id augue vitae urna tristique tempus.<\/p><p>Etiam accumsan urna a mauris dapibus, nec aliquet nunc convallis. Phasellus eget justo et libero ultrices posuere. Cras euismod, arcu nec congue convallis, ipsum nunc cursus nibh, vel condimentum sapien orci non libero. Integer ullamcorper felis sit amet felis placerat, eu convallis lorem iaculis.<\/p><p>Phasellus ac eros at urna condimentum lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed bibendum, sapien a venenatis fermentum, mauris augue cursus turpis, vitae elementum massa orci sit amet massa. In hac habitasse platea dictumst.<\/p><p>Curabitur tincidunt, felis a elementum tincidunt, ex felis fermentum dui, eget pulvinar arcu eros eu eros. Vestibulum sollicitudin pretium velit, eget volutpat justo fermentum sit amet. Pellentesque in nulla in nisi dictum interdum.<\/p><p>Integer a ipsum vitae urna varius egestas. Integer laoreet, sapien eget vehicula vehicula, odio lorem scelerisque magna, nec gravida libero nulla eget risus. Nulla facilisi. Donec at magna ut nulla pharetra cursus. Curabitur auctor, tellus in congue vestibulum, lacus lacus convallis justo, at fermentum libero felis nec ligula.<\/p>","buttons":[],"theme":{"background_color":"white","use_bg":false,"blockMaxWidth":"max-w-3xl","blockMaxWidthSm":null,"blockMaxWidthMd":null,"blockMaxWidthLg":null,"blockMaxWidthXl":null,"blockMaxWidth2xl":null,"blockVerticalPadding":"py-8","blockVerticalPaddingSm":null,"blockVerticalPaddingMd":null,"blockVerticalPaddingLg":"lg:py-24","blockVerticalPaddingXl":null,"blockVerticalPadding2xl":null,"blockVerticalMargin":null,"blockVerticalMarginSm":null,"blockVerticalMarginMd":null,"blockVerticalMarginLg":null,"blockVerticalMarginXl":null,"blockVerticalMargin2xl":null}},"path":"mason.block"}}]}'),
            'excerpt' => $this->faker->paragraph(),
            'extras' => [
                'show_featured_image' => true,
            ],
            'published_at' => Carbon::now(),
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
        ];
    }
}
