<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'published_at',
        'author_id',
        'category_id',
        'extras',
        'meta',
    ];

    protected $casts = [
        'content' => 'array',
        'extras' => 'array',
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaConversion('lg')
            ->width(1280)
            ->format('jpg');

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 720, 488)
            ->format('jpg');

        $this->addMediaConversion('square')
            ->fit(Fit::Crop, 600, 600)
            ->format('jpg');

        $this->addMediaConversion('icon')
            ->fit(Fit::Crop, 90, 90)
            ->format('jpg');
    }

    public function permalink(): string
    {
        return route('cms.blog.post', $this->slug);
    }
}
