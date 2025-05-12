<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class SmartPage extends Model
{
    use HasTranslations;

    protected $table = 'smartpages';

    protected $fillable = [
        'page',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public array $translatable = ['value'];

    /**
     * Prefisso utilizzato per tutte le chiavi di cache.
     */
    private const CACHE_PREFIX = 'smartpage.';

    /**
     * Boot del modello: aggiungiamo i listener per gli eventi del modello
     */
    protected static function boot()
    {
        parent::boot();

        // Quando un record viene aggiornato o salvato, cancelliamo la cache correlata
        static::saved(function (self $smartPage) {
            self::clearCache($smartPage->page, $smartPage->key);
        });

        static::deleted(function (self $smartPage) {
            self::clearCache($smartPage->page, $smartPage->key);
        });
    }

    public function setAttribute($key, $value)
    {
        // Annulliamo l'effetto del trait HasTranslations
        return parent::setAttribute($key, $value);
    }

    /**
     * Recupera tutte le impostazioni per una determinata pagina e le formatta come array chiave-valore.
     *
     * @param  string  $page  Il nome della pagina di cui recuperare le impostazioni
     * @return array Le impostazioni formattate con chiave => traduzioni
     */
    public static function getPageValues(string $page): array
    {

        $settings = [];

        static::where('page', $page)
            ->get()
            ->each(function (self $setting) use (&$settings) {

                if (empty($setting->translations['value'])) {
                    $value = $setting->toArray()['value'];
                } else {
                    $value = $setting->translations['value'];
                }

                $settings[$setting->key] = $value;
            });

        return $settings;
    }

    /**
     * Recupera un valore di configurazione dal database.
     *
     * @param  string  $key  La chiave di configurazione nel formato pagina.impostazione.sotto_chiave
     * @param  mixed  $default  Il valore predefinito da restituire se la configurazione non è trovata
     * @return mixed Il valore di configurazione
     */
    public static function get(string $key, mixed $default = null, ?string $locale = null): mixed
    {
        [$page, $setting, $subKey] = self::parseKey($key);

        $data = self::fetchSetting($page, $setting, $locale);

        if (empty($data) && $subKey === $setting) {
            return $default;
        }

        $value = $subKey === $setting ? $data : data_get($data, substr($subKey, strlen($setting) + 1), null);

        return $value ?? $default;
    }

    /**
     * Imposta un valore di configurazione nel database.
     *
     * @param  string  $key  La chiave di configurazione nel formato pagina.impostazione
     * @param  mixed  $value  Il valore da salvare
     */
    public static function set(string $key, mixed $value): void
    {
        [$page, $setting] = self::parseKey($key);

        static::updateOrCreate(
            [
                'page' => $page,
                'key' => $setting,
            ],
            [
                'value' => $value,
            ]
        );

        // La cache viene cancellata automaticamente tramite l'evento saved
    }

    /**
     * Cancella la cache associata a una specifica pagina e chiave.
     *
     * @param  string  $page  La pagina
     * @param  string  $key  La chiave dell'impostazione
     */
    public static function clearCache(string $page, string $key): void
    {
        // Cancella la cache per tutte le lingue disponibili
        $locales = get_supported_locales();

        foreach ($locales as $locale) {
            $cacheKey = "smartpage_{$locale}_{$page}.{$key}";
            Cache::forget($cacheKey);
        }
    }

    /**
     * Suddivide una chiave di configurazione nelle sue componenti.
     *
     * @param  string  $key  La chiave di configurazione
     * @return array Array contenente [pagina, impostazione, percorso_completo]
     */
    protected static function parseKey(string $key): array
    {
        $keyParts = explode('.', $key);
        $page = array_shift($keyParts);
        $setting = $keyParts[0] ?? null;
        $subKey = implode('.', $keyParts);

        return [$page, $setting, $subKey];
    }

    /**
     * Recupera un'impostazione specifica dal database.
     *
     * @param  string  $page  La pagina
     * @param  string  $setting  La chiave dell'impostazione
     * @return mixed Il valore dell'impostazione o un array vuoto se non trovata
     */
    protected static function fetchSetting(string $page, string $setting, ?string $locale = null): mixed
    {
        $item = static::where('page', $page)
            ->where('key', $setting)
            ->first();

        $locale = $locale ?? app()->getLocale();

        return $item ? $item->getTranslation('value', $locale) : [];
    }
}
