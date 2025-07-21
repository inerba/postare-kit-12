<?php

namespace App\Filament\Resources\MenuResource\MenuTypeHandlers;

class PlaceholderType implements MenuTypeInterface
{
    public function getName(): string
    {
        return __('simple-menu-manager.handlers.placeholder.name');
    }

    /**
     * Restituisce i campi specifici per il tipo di menu "placeholder".
     *
     * @return array<string, mixed> Un array di campi specifici per il tipo di menu "placeholder".
     */
    public static function getFields(): array
    {
        return [];
    }
}
