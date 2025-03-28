<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    // Questo evita che venga mostrato il "Nuovo pagina" al maschile
    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return __('pages.resources.page.new');
    }

    // Come sopra, ma per il breadcrumb
    public function getBreadcrumb(): string
    {
        return static::$breadcrumb ?? __('pages.resources.page.new');
    }
}
