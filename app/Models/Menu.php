<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
