<?php

namespace App\Filament\Resources\MenuResource\MenuTypeHandlers;

interface MenuTypeInterface
{
    /**
     * Get the name of the menu type.
     */
    public function getName(): string;

    /**
     * Get the fields for the menu type.
     *
     * @return array<string, mixed> An array of fields associated with the menu type.
     */
    public static function getFields(): array;
}
