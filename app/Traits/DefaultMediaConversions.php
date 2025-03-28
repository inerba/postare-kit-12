<?php

namespace App\Traits;

use Spatie\Image\Enums\Fit;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait DefaultMediaConversions
{
    /**
     * Register the media collections.
     * https://spatie.be/docs/laravel-medialibrary/v11/converting-images/defining-conversions
     *
     * @throws InvalidManipulation
     */
    public function registerCustomMediaCollections(?Media $media = null): void
    {
        $format = config('postare-kit.media.format', 'jpg');

        if (config('postare-kit.media.conversions.xl', false)) {
            $this->addMediaConversion('xl')
                ->format($format)
                ->width(config('postare-kit.media.conversions.xl', 1920));
        }

        if (config('postare-kit.media.conversions.lg', false)) {
            $this->addMediaConversion('lg')
                ->format($format)
                ->width(config('postare-kit.media.conversions.lg', 1280));
        }

        if (config('postare-kit.media.conversions.md', false)) {
            $this->addMediaConversion('md')
                ->format($format)
                ->width(config('postare-kit.media.conversions.md', 400));
        }

        if (config('postare-kit.media.conversions.sm', false)) {
            $this->addMediaConversion('sm')
                ->format($format)
                ->width(config('postare-kit.media.conversions.sm', 200));
        }

        if (config('postare-kit.media.conversions.xs', false)) {
            $this->addMediaConversion('xs')
                ->format($format)
                ->width(config('postare-kit.media.conversions.xs', 100));
        }

        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Crop, 150, 150)
            ->format('jpg');

        // Questa conversione è usata per le immagini nelle tabelle del backoffice
        $this->addMediaConversion('icon')
            ->fit(Fit::Crop, 90, 90)
            ->format('jpg');
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaCollections(?Media $media = null): void
    {
        $this->registerCustomMediaCollections($media);
        parent::registerMediaCollections($media);
    }
}
