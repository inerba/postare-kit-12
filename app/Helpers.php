<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

/**
 * Helpers per il menù
 */
if (! function_exists('active_route')) {
    /**
     * Controlla se la rotta corrente corrisponde a quella specificata.
     *
     * @param  string  $route  La rotta da verificare.
     * @param  bool  $active  Valore da restituire se la rotta è attiva.
     * @param  bool  $default  Valore da restituire se la rotta non è attiva.
     * @return bool Il valore attivo o predefinito in base alla corrispondenza della rotta.
     */
    function active_route(string $route, $active = true, $default = false)
    {
        $current = (string) str(url()->current())->remove(config('app.url'))->trim('/');
        $route = (string) str($route)->trim('/');

        if ($current === $route) {
            return $active;
        }

        return $default;
    }
}

if (! function_exists('is_panel_auth_route')) {
    function is_panel_auth_route(): bool
    {
        $authRoutes = [
            '/login',
            '/password-reset',
            '/register',
            '/email-verification',
        ];

        return Str::of(Request::path())->contains($authRoutes);
    }
}

if (! function_exists('removeEmptyValues')) {
    /**
     * Rimuove i valori vuoti da un array, mantenendo 0 e '0' come validi.
     *
     * @param  array<mixed>  $array  L'array da filtrare.
     * @return array<mixed> L'array filtrato senza valori vuoti.
     */
    function removeEmptyValues(array $array): array
    {
        // Applica array_map per garantire la ricorsività su tutti gli elementi
        return array_filter(array_map(function ($value) {
            return is_array($value) ? removeEmptyValues($value) : $value;
        }, $array), function ($value) {
            // Filtra valori vuoti mantenendo 0 e '0' come validi
            return ! empty($value) || $value === 0 || $value === '0';
        });
    }
}

/**
 * Helper per la pagina
 */
if (! function_exists('page_url')) {
    /**
     * Get the permalink of a page by its ID or slug.
     * Defaults to relative url.
     *
     * @param  int|string  $identifier  The post ID or slug
     * @param  bool  $absolute  Whether to return the absolute url
     */
    function page_url(int|string $identifier, bool $absolute = false): string
    {
        if ($absolute) {
            $key = "page_url.$identifier.absolute";
        } else {
            $key = "page_url.$identifier";
        }

        return Cache::remember(
            $key,
            config('postare-kit.cache.default_duration'),
            function () use ($identifier, $absolute) {
                if (is_int($identifier)) {
                    $page = App\Models\Page::find($identifier);
                } else {
                    $page = App\Models\Page::where('slug', $identifier)->first();
                }

                if ($absolute) {
                    return $page?->permalink;
                } else {
                    return $page?->relativePermalink;
                }
            }
        ) ?? '';
    }
}
