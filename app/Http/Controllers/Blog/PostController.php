<?php

namespace App\Http\Controllers\Blog;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Mostra il post specificato.
     *
     * @param  Post  $post  Il post da visualizzare.
     * @return \Illuminate\View\View La vista del post.
     */
    public function __invoke(string $category, Post $post): \Illuminate\View\View
    {
        // Verifica se il post è pubblicato
        if ($post->published_at > now()) {
            return abort(404);
        }

        return view('news.post', [
            'post' => $post,
        ]);
    }
}
