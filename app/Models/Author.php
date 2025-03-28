<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\CropPosition;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Author extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'bio',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // protected $appends = [
    //     'thumbnail',
    //     'icon',
    // ];

    public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->format('jpg')
            ->width(150)
            ->height(150)
            ->crop(150, 150, CropPosition::Center);

        $this->addMediaConversion('icon')
            ->format('jpg')
            ->width(90)
            ->height(90)
            ->crop(90, 90, CropPosition::Center);
    }
}
