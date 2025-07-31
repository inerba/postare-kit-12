<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\DefaultMediaConversions;
use App\Traits\HasMeta;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read object $seo
 * @property-read object $og
 * @property-read string $relativePermalink
 * @property-read string $permalink
 * @property-read Page|null $parent
 */
class Page extends Model implements HasMedia
{
    use DefaultMediaConversions;
    use HasMeta;
    use HasUniqueSlug;
    use InteractsWithMedia;

    protected string $content_type = 'page';

    protected $fillable = [
        'title',
        'slug',
        'lead',
        'content',
        'meta',
        'custom_fields',
        'extras',
        'sort_order',
        'parent_id',
        'author_id',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'extras' => 'array',
        'content' => 'array',
        'meta' => 'array',
    ];

    protected $with = [
        'parent',
    ];

    /**
     * @property-read string $permalink
     *
     * @return Attribute<string, never>
     */
    protected function permalink(): Attribute
    {
        $slugs = $this->getParentSlugs($this);
        $slugPath = implode('/', $slugs);

        return Attribute::make(
            get: fn () => route('cms.page', [
                'slug' => $slugPath,
            ]),
        );
    }

    /**
     * @property-read string $relativePermalink
     *
     * @return Attribute<string, never>
     */
    protected function relativePermalink(): Attribute
    {
        $slugs = $this->getParentSlugs($this);
        $slugPath = implode('/', $slugs);

        return Attribute::make(
            get: fn () => route('cms.page', [
                'slug' => $slugPath,
            ], false),
        );
    }

    public function hasFeaturedImages(): bool
    {
        return $this->getMedia('featured_images')->count() > 0;
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->registerCustomMediaCollections($media);
    }

    /**
     * Get the author of the page.
     *
     * @return BelongsTo<Author, Page>
     */
    public function author(): BelongsTo
    {
        /** @var BelongsTo<Author, Page> */
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Get the parent page of the current page.
     *
     * @return BelongsTo<Page, Page>
     */
    public function parent(): BelongsTo
    {
        /** @var BelongsTo<Page, Page> */
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Ottiene le pagine figlie della pagina corrente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Page, \App\Models\Page>
     */
    public function children(): HasMany
    {
        /** @var HasMany<Page, Page> */
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the view name for the current page.
     *
     * @return string View name
     */
    public function getViewName(): string
    {
        $defaultView = 'pages.page';

        // Check if a custom view exists
        $customView = 'pages.'.$this->slug;

        if (view()->exists($customView)) {
            return $customView;
        }

        return $defaultView;
    }

    public function hasCustomView(): bool
    {
        $customView = 'pages.'.$this->slug;

        return view()->exists($customView);
    }

    /**
     * Get the parent slugs for the current page.
     *
     * @param  self  $page  Istanza corrente della pagina.
     * @param  array<string>  $slugs  Slug accumulati.
     * @return array<string> Array di slug che rappresentano la gerarchia della pagina.
     */
    private function getParentSlugs(self $page, array $slugs = []): array
    {
        if ($page->parent) {
            $slugs = $this->getParentSlugs($page->parent, $slugs);
        }

        $slugs[] = $page->slug;

        return $slugs;
    }
}
