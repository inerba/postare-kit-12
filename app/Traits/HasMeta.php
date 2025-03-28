<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;

trait HasMeta
{
    protected function seo(): Attribute
    {
        return Attribute::make(
            get: fn () => (object) [
                // 'title' => Arr::get($this->meta, 'seo.tag_title', $this->title),
                'title' => optional($this->meta)['seo']['tag_title'] ?? $this->title,
                // 'meta_description' => Arr::get($this->meta, 'seo.meta_description', $this->getDefaultMetaDescription()),
                'meta_description' => optional($this->meta)['seo']['meta_description'] ?? $this->getDefaultMetaDescription(),
                // 'author' => Arr::get($this->meta, 'seo.author', config('postare-kit.seo.author', config('app.name'))),
                'author' => optional($this->meta)->seo->author ?? config('postare-kit.seo.author', config('app.name')),
            ]
        );
    }

    protected function og(): Attribute
    {
        return Attribute::make(
            get: fn () => (object) [
                // 'type' => Arr::get($this->meta, 'og.type', config('postare-kit.og.type', 'article')),
                'type' => optional($this->meta)['og']['type'] ?? config('postare-kit.og.type', 'article'),
                // 'title' => Arr::get($this->meta, 'og.title', $this->seo->title),
                'title' => optional($this->meta)['og']['title'] ?? $this->seo->title,
                // 'description' => Arr::get($this->meta, 'og.description', $this->seo->meta_description),
                'description' => optional($this->meta)['og']['description'] ?? $this->seo->meta_description,

                'image' => $this->getOgImage(),
            ]
        );
    }

    protected function getDefaultMetaDescription(): string
    {
        return str(tiptap_converter()->asText($this->content))->trim()->replaceMatches('/\s+/', ' ')->limit(160)->toHtmlString();
    }

    protected function getOgImage()
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
