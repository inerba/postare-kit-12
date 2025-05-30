<?php

namespace App\Http\Controllers\Blog;

use App\Models\Category;
use App\Models\Post;

class BlogCategoryController extends Controller
{
    public function __invoke(Category $category)
    {
        // // Verifica se il blog è abilitato
        // if (! db_config('blogconfig.enabled', true)) {
        //     return abort(404);
        // }

        $posts = Post::query()
            ->where('category_id', $category->id)
            ->where('published_at', '<=', now())
            ->with([
                'category',
                'media',
            ])
            ->get();

        return view('news.category', [
            'posts' => $posts,
        ]);
    }
}
