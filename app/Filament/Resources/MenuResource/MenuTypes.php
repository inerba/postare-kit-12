<?php

namespace App\Filament\Resources\MenuResource;

use App\Filament\Resources\MenuResource\MenuTypeHandlers\MenuTypeInterface;

/**
 * Questa classe fornisce metodi per ottenere i tipi di menu e i campi associati a un tipo specificato.
 */
class MenuTypes
{
    /**
     * Restituisce un array di tipi di menu disponibili.
     *
     * @return array<string, string> Un array associativo con i tipi di menu come chiavi e i loro nomi come valori.
     */
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

    /**
     * Restituisce i campi associati a un tipo di menu specificato.
     *
     * @param  string|null  $type  Il tipo di menu per il quale ottenere i campi. Se non specificato, verrà usato 'link'.
     * @return array<string, mixed> Un array di campi associati al tipo di menu.
     *
     * @throws \InvalidArgumentException Se il tipo specificato non esiste.
     */
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

    /**
     * Restituisce un array di handler dei tipi di menu configurati.
     *
     * @return array<string, string> Un array associativo con i tipi di menu come chiavi e le classi degli handler come valori.
     */
    public static function getHandlers()
    {
        return config('simple-menu-manager.handlers', []);
    }
}
