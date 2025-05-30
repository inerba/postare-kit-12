<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function boot()
    {
        self::creating(function ($model) {
            $model->slug = str($model->name)->slug();
        });

        parent::boot();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function permalink(): string
    {
        return route('cms.blog.category', $this->slug);
    }
}
