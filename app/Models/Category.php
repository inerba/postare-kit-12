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
}
