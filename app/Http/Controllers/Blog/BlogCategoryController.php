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
        $category = Category::find($category->id);

        if (! $category) {
            return abort(404);
        }

        $post_per_page = $category->extras['post_per_page'] ?? 12;

        $posts = $category->posts()
            // ->where('published_at', '<=', now())
            ->with(['category', 'media', 'author'])
            ->orderBy('published_at', 'desc')
            ->simplePaginate($post_per_page);

        return view('news.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}
