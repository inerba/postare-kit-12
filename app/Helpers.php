<?php

use App\Models\SmartPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

/**
 * Helpers per il menù
 */
if (! function_exists('active_route')) {
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
    function page_url(int|string $identifier, bool $absolute = false): ?string
    {
        if ($absolute) {
            $key = "page_url.$identifier.absolute";
        } else {
            $key = "page_url.$identifier";
        }

        return Cache::remember(
            $key,
            config('filament-blog.cache.default_duration'),
            function () use ($identifier, $absolute) {
                if (is_int($identifier)) {
                    $page = App\Models\Page::find($identifier);
                } else {
                    $page = App\Models\Page::where('slug', $identifier)->first();
                }

                if ($absolute) {
                    return $page?->permalink;
                } else {
                    return $page?->relative_permalink;
                }
            }
        ) ?? '';
    }
}

if (! function_exists('smartpage')) {
    /**
     * Recupera il valore di una chiave specifica per una pagina.
     * Tiene conto della lingua corrente per la memorizzazione nella cache.
     *
     * @param  string  $page  Il nome della pagina di cui recuperare il valore
     * @param  string  $key  La chiave del valore da recuperare
     * @param  mixed  $default  Il valore predefinito da restituire se la pagina o la chiave non esistono
     * @return mixed Il valore della chiave specificata o il valore predefinito
     */
    function smartpage(string $key, ?string $locale = null, mixed $default = null): mixed
    {
        $currentLocale = $locale ?? app()->getLocale();
        $cacheKey = "smartpage_{$currentLocale}_{$key}";

        // return Cache::rememberForever(
        //     $cacheKey,
        //     fn() => SmartPage::get($key, $default, $locale)
        // );

        return SmartPage::get($key, $default, $locale);
    }

    if (! function_exists('get_supported_locales')) {
        /**
         * Recupera l'elenco delle lingue supportate dall'applicazione.
         *
         * @return array L'elenco delle lingue supportate
         */
        function get_supported_locales(): array
        {
            return array_keys(config('laravellocalization.supportedLocales', [config('app.locale')]));
        }
    }
}
