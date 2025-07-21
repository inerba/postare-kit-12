<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    /**
     * Mostra la pagina corrispondente allo slug fornito.
     *
     * @param  string  $slug  Lo slug della pagina da visualizzare.
     * @return \Illuminate\View\View|null La vista della pagina trovata, o null se non esiste.
     */
    public function __invoke($slug): ?\Illuminate\View\View
    {
        $slugs = explode('/', $slug);

        $page = $this->findPage($slugs);

        $viewName = $page->getViewName();

        // Restituisci la vista con la pagina trovata
        return view($viewName, ['page' => $page]);
    }

    /**
     * Trova la pagina corrispondente agli slug forniti.
     *
     * @param  string[]  $slugs  Un array di slug (stringhe) che rappresentano la gerarchia della pagina.
     * @param  Page|null  $parentPage  La pagina padre, se esiste.
     * @return Page|null La pagina trovata o null se non esiste.
     */
    private function findPage(array $slugs, ?Page $parentPage = null): ?Page
    {
        // Prendi il primo slug dall'array
        $slug = array_shift($slugs);

        // Se abbiamo una pagina padre, cerchiamo una sottopagina
        if ($parentPage) {
            /** @var Page $page */
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
