<?php

namespace App\Models;

use App\Traits\DefaultMediaConversions;
use App\Traits\HasCustomFields;
use App\Traits\HasMenuItem;
use App\Traits\HasMeta;
use App\Traits\HasUniqueSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use DefaultMediaConversions;
    use HasCustomFields;
    use HasMenuItem;
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

    // Getter for permalink
    protected function permalink(): Attribute
    {
        $slugs = $this->getParentSlugs($this);
        $slugPath = implode('/', $slugs);

        return Attribute::make(
            get: fn() => route('cms.page', [
                'slug' => $slugPath,
            ]),
        );
    }

    protected function relativePermalink(): Attribute
    {
        $slugs = $this->getParentSlugs($this);
        $slugPath = implode('/', $slugs);

        return Attribute::make(
            get: fn() => route('cms.page', [
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    // Get the parent page
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Get the view name for the current page.
     *
     * @return string View name
     */
    public function getViewName(): string
    {
        $defaultView = 'filament-blog::page';

        // Check if a custom view exists
        $customView = 'filament-blog::page.' . $this->slug;

        if (view()->exists($customView)) {
            return $customView;
        }

        return $defaultView;
    }

    public function hasCustomView(): bool
    {
        $customView = 'filament-blog::page.' . $this->slug;

        return view()->exists($customView);
    }

    private function getParentSlugs(Page $page, array $slugs = []): array
    {
        if ($page->parent) {
            $slugs = $this->getParentSlugs($page->parent, $slugs);
        }

        $slugs[] = $page->slug;

        return $slugs;
    }
}
