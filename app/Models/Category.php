<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'extras',
    ];

    protected $casts = [
        'extras' => 'array',
    ];

    // public static function boot(): void
    // {
    //     self::creating(function ($model) {
    //         $model->slug = str($model->name)->slug();
    //     });

    //     parent::boot();
    // }

    /**
     * Get the posts that belong to this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post, Category>
     */
    public function posts(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<Post, Category> */
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the permalink for the post.
     *
     * @return Attribute<string, never>
     */
    protected function permalink(): Attribute
    {
        return Attribute::make(
            get: fn () => route('cms.blog.category', $this->slug),
        );
    }
}
