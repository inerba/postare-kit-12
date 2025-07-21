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

        $posts = $category->posts()
            // ->where('published_at', '<=', now())
            ->with(['category', 'media', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // // Raggruppa i post in array di 6
        // $posts = $posts->chunk(6);

        return view('news.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}
