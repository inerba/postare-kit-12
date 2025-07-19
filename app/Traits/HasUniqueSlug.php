<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasUniqueSlug
 * Provides functionality to generate a unique slug for a model.
 */
trait HasUniqueSlug
{
    /**
     * Generates a unique slug for the model.
     *
     * @param  Model  $modelInstance  The model instance for which to generate the slug.
     * @param  string  $fieldValue  The value used to generate the base slug.
     * @param  ?string  $slugField  The name of the slug field in the database.
     */
    protected function createUniqueSlug(Model $modelInstance, string $fieldValue, ?string $slugField = null): string
    {
        $slugField = $slugField ?? $this->getSlugField();
        $baseSlug = Str::slug($fieldValue);
        $slug = $baseSlug;
        $count = 1;

        while ($modelInstance->newQuery()->where($slugField, $slug)
            ->when($this->exists, fn ($query) => $query->where('id', '<>', $this->id))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Overrides the save method to ensure the slug is unique before saving.
     */
    // public function save(array $options = []): bool
    // {
    //     $slugField = $this->getSlugField();
    //     $slugBaseField = $this->getSlugBaseField();

    //     if (! $this->getAttribute($slugField)) {
    //         $this->setAttribute($slugField, $this->createUniqueSlug($this, $this->{$slugBaseField}));
    //     } elseif ($this->isDirty($slugField)) {
    //         $this->setAttribute($slugField, $this->createUniqueSlug($this, $this->getAttribute($slugField)));
    //     }

    //     return parent::save($options);
    // }
    public function save(array $options = []): bool
    {
        $slugField = $this->getSlugField();
        $slugBaseField = $this->getSlugBaseField();

        // Gestione per campi non translatable
        if (! $this->getAttribute($slugField)) {
            $this->setAttribute($slugField, $this->createUniqueSlug($this, $this->{$slugBaseField}));
        } elseif ($this->isDirty($slugField)) {
            $this->setAttribute($slugField, $this->createUniqueSlug($this, $this->getAttribute($slugField)));
        }

        return parent::save($options);
    }

    /**
     * Retrieves the field name used as the base for the slug.
     * Can be overridden in the using model to change the default field.
     */
    protected function getSlugBaseField(): string
    {
        return $this->slugBaseField ?? 'title';
    }

    /**
     * Retrieves the field name used as the slug.
     * Can be overridden in the using model to change the default field.
     */
    protected function getSlugField(): string
    {
        return $this->slugField ?? 'slug';
    }
}
