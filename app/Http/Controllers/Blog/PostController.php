<?php

namespace App\Http\Controllers\Blog;

use App\Models\Blog\Post;

class PostController extends Controller
{
    public function __invoke(Post $post)
    {
        // Verifica se il blog è abilitato
        if (! db_config('blogconfig.enabled', true)) {
            return abort(404);
        }

        // Verifica se il post è pubblicato
        if ($post->published_at > now()) {
            return abort(404);
        }

        return view('portal.blog.postView', [
            'post' => $post,
        ]);
    }
}
