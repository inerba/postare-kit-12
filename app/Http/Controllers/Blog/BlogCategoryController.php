<?php

namespace App\Http\Controllers\Blog;

use App\Models\Category;
use App\Models\Post;

class BlogCategoryController extends Controller
{
    /**
     * Mostra i post della categoria specificata.
     *
     * @param  Category  $category  La categoria di cui mostrare i post.
     * @return \Illuminate\View\View La vista con i post della categoria.
     */
    public function __invoke(Category $category): \Illuminate\View\View
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
