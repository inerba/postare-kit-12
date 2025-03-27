<?php

declare(strict_types=1);

namespace App;

class Helper
{
    /**
     * Formatta un numero come valuta
     */
    public static function formatCurrency(float $amount, string $currency = 'EUR'): string
    {
        return number_format($amount, 2, ',', '.').' €';
    }

    /**
     * Formatta una data in formato italiano
     */
    public static function formatDate(\DateTime|string $date): string
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        return $date->format('d/m/Y');
    }

    /**
     * Trunca una stringa alla lunghezza specificata
     */
    public static function truncate(string $string, int $length = 100, string $suffix = '...'): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        return substr($string, 0, $length).$suffix;
    }

    /**
     * Genera un slug da una stringa
     */
    public static function slugify(string $string): string
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);

        return trim($string, '-');
    }

    /**
     * Verifica se una stringa è un URL valido
     */
    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Genera un nome file univoco
     */
    public static function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        return uniqid().'_'.time().'.'.$extension;
    }
}
