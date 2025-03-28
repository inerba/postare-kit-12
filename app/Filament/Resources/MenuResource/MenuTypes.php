<?php

namespace App\Filament\Resources\MenuResource;

use App\Filament\Resources\MenuResource\MenuTypeHandlers\MenuTypeInterface;

/**
 * Questa classe fornisce metodi per ottenere i tipi di menu e i campi associati a un tipo specificato.
 */
class MenuTypes
{
    public static function getTypes(): array
    {
        $handlers = self::getHandlers();

        $types = [];

        foreach ($handlers as $key => $className) {
            $instance = app($className); // Usa il Container IoC per creare l'istanza
            if ($instance instanceof MenuTypeInterface) {
                $types[$key] = $instance->getName();
            }
        }

        return $types;
    }

    public static function getFieldsByType(?string $type = null): array
    {
        $type = $type ?: 'link';

        $handlers = self::getHandlers();

        if (! isset($handlers[$type])) {
            throw new \InvalidArgumentException("Tipo {$type} non trovato.");
        }

        $handlerClass = $handlers[$type];

        return app($handlerClass)::getFields();
    }

    public static function getHandlers()
    {
        return config('simple-menu-manager.handlers', []);
    }
}
