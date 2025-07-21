<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\HtmlString;

trait HasMeta
{
    /**
     * Restituisce i metadati SEO per il modello.
     *
     * @return Attribute<object, never>
     */
    protected function seo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): object => (object) [
                'title' => optional($this->meta)['seo']['tag_title'] ?? $this->title,
                'meta_description' => optional($this->meta)['seo']['meta_description'] ?? $this->getDefaultMetaDescription(),
                'author' => optional($this->meta)->seo->author ?? config('postare-kit.seo.author', config('app.name')),
            ]
        );
    }

    /**
     * Restituisce i metadati Open Graph per il modello.
     *
     * @return Attribute<object, never>
     */
    protected function og(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): object => (object) [
                'type' => optional($this->meta)['og']['type'] ?? config('postare-kit.og.type', 'article'),
                'title' => optional($this->meta)['og']['title'] ?? ($this->seo->title ?? null),
                'description' => optional($this->meta)['og']['description'] ?? ($this->seo->meta_description ?? null),
                'image' => $this->getOgImage(),
            ]
        );
    }

    /**
     * Restituisce la descrizione predefinita per i metadati SEO.
     *
     * @return HtmlString La descrizione predefinita.
     */
    protected function getDefaultMetaDescription(): HtmlString
    {
        return str(tiptap_converter()->asText($this->content))->trim()->replaceMatches('/\s+/', ' ')->limit(160)->toHtmlString();
    }

    /**
     * Restituisce l'URL dell'immagine Open Graph o dell'immagine in evidenza.
     *
     * @return string|null L'URL dell'immagine Open Graph o null se non disponibile.
     */
    protected function getOgImage(): ?string
    {
        if ($this->hasMedia('og_image')) {
            return $this->getFirstMediaUrl('og_image');
        }

        if ($this->hasMedia('featured_images')) {
            return $this->getFirstMediaUrl('featured_images');
        }

        return null;
    }
}
