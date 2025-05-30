<?php

namespace App\Http\Controllers\Blog;

use App\Models\Post;

class BlogHomepageController extends Controller
{
    public function __invoke()
    {
        // // Verifica se il blog è abilitato
        // if (! db_config('blogconfig.enabled', true)) {
        //     return abort(404);
        // }

        // Ottieni le impostazioni del blog
        $post_per_page = db_config('blogconfig.posts_per_page', 5);

        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->with([
                'category',
                'media',
            ])
            ->orderByDesc('created_at')
            ->simplePaginate($post_per_page);

        return view('portal.blog.homepage', [
            'posts' => $posts,
        ]);
    }
}
