<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function __invoke($slug)
    {
        $slugs = explode('/', $slug);

        $page = $this->findPage($slugs);

        $viewName = $page->getViewName();

        // Restituisci la vista con la pagina trovata
        return view($viewName, ['page' => $page]);
    }

    private function findPage(array $slugs, ?Page $parentPage = null)
    {
        // Prendi il primo slug dall'array
        $slug = array_shift($slugs);

        // Se abbiamo una pagina padre, cerchiamo una sottopagina
        if ($parentPage) {
            $page = $parentPage->children()->where('slug', $slug)->firstOrFail();
        } else {
            // Altrimenti, cerchiamo una pagina padre
            $page = Page::where('slug', $slug)->whereNull('parent_id')->firstOrFail();
        }

        // Se ci sono ancora slug nell'array, cerchiamo la sottopagina corrispondente
        if (! empty($slugs)) {
            return $this->findPage($slugs, $page);
        }

        // Se non ci sono più slug, abbiamo trovato la nostra pagina
        return $page;
    }
}
